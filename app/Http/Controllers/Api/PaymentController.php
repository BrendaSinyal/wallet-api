<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|string|max:100|unique:payment_transactions,invoice_id',
            'invoice_number' => 'required|string|max:100',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|max:10',
        ]);

        $paymentReference = 'PAY-' . $validated['invoice_number'] . '-' . strtoupper(Str::random(6));
        $paymentUrl = url('/pay/' . $paymentReference);

        $payment = PaymentTransaction::create([
            'invoice_id' => $validated['invoice_id'],
            'invoice_number' => $validated['invoice_number'],
            'customer_name' => $validated['customer_name'] ?? null,
            'customer_email' => $validated['customer_email'] ?? null,
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'payment_reference' => $paymentReference,
            'payment_url' => $paymentUrl,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment created successfully',
            'data' => [
                'payment_reference' => $payment->payment_reference,
                'payment_url' => $payment->payment_url,
                'status' => $payment->status,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
            ]
        ], 201);
    }

    public function show(string $reference)
    {
        Log::info('SHOW PAYMENT DEBUG', [
            'reference' => $reference,
            'db_connection' => config('database.default'),
            'db_database' => config('database.connections.' . config('database.default') . '.database'),
        ]);

        $payment = PaymentTransaction::where('payment_reference', $reference)->first();

        Log::info('SHOW PAYMENT RESULT', [
            'found' => $payment ? true : false,
            'payment_reference' => $payment->payment_reference ?? null,
        ]);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found',
                'reference' => $reference,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment retrieved successfully',
            'data' => [
                'payment_reference' => $payment->payment_reference,
                'payment_url' => $payment->payment_url,
                'status' => $payment->status,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
            ]
        ], 200);
    }
}