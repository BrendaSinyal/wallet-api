<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentPageController extends Controller
{
    public function show(string $reference)
    {
        $payment = PaymentTransaction::where('payment_reference', $reference)->first();

        if (!$payment) {
            abort(404, 'Payment not found');
        }

        return view('payment.show', compact('payment'));
    }

public function checkout(Request $request, string $reference)
{
    $payment = PaymentTransaction::where('payment_reference', $reference)->firstOrFail();

    $payload = [
        'appId' => env('WALLET_APP_ID'),
        'outTradeNo' => $payment->payment_reference,
        'amount' => (float) $payment->amount,
        'currency' => $payment->currency,
        'subject' => 'Invoice ' . $payment->invoice_number,
        'notifyUrl' => url('/api/v1/webhooks/payments'),
        'returnUrl' => url('/pay/' . $payment->payment_reference),
    ];

    $signResponse = Http::post(env('WALLET_BASE_URL') . '/payment/generateSign', [
        ...$payload,
        'appSecret' => env('WALLET_SECRET'),
    ]);

    if (!$signResponse->successful()) {
        dd([
            'step' => 'generateSign',
            'status' => $signResponse->status(),
            'body' => $signResponse->body(),
            'json' => $signResponse->json(),
        ]);
    }

    $signResult = $signResponse->json();

    if (empty($signResult['sign'])) {
        dd([
            'step' => 'generateSign-no-sign',
            'json' => $signResult,
        ]);
    }

    $createOrderResponse = Http::post(env('WALLET_BASE_URL') . '/payment/createOrder', [
        ...$payload,
        'sign' => $signResult['sign'],
    ]);

    if (!$createOrderResponse->successful()) {
        dd([
            'step' => 'createOrder',
            'status' => $createOrderResponse->status(),
            'body' => $createOrderResponse->body(),
            'json' => $createOrderResponse->json(),
        ]);
    }

    $result = $createOrderResponse->json();

    if (empty($result['payUrl'])) {
        dd([
            'step' => 'createOrder-no-payUrl',
            'json' => $result,
        ]);
    }

    $payment->update([
        'external_transaction_id' => $result['tradeNo'] ?? null,
        'wallet_checkout_url' => $result['payUrl'] ?? null,
        'payment_method' => 'wallet',
    ]);

    return redirect($result['payUrl']);
}

}