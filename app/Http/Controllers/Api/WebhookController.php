<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $signature = $request->header('X-Signature');
        $secret = env('WEBHOOK_SECRET');

        $computedSignature = hash_hmac('sha256', $request->getContent(), $secret);

        if (!$signature || !hash_equals($computedSignature, $signature)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid signature',
            ], 401);
        }

        $payload = $request->validate([
            'payment_reference' => 'required|string',
            'external_transaction_id' => 'nullable|string',
            'status' => 'required|string|in:pending,paid,failed,expired',
            'paid_at' => 'nullable|date',
        ]);

        $payment = PaymentTransaction::where('payment_reference', $payload['payment_reference'])->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found',
            ], 404);
        }

        $payment->update([
            'external_transaction_id' => $payload['external_transaction_id'] ?? $payment->external_transaction_id,
            'status' => $payload['status'],
            'paid_at' => $payload['status'] === 'paid' && !empty($payload['paid_at'])
                ? Carbon::parse($payload['paid_at'])
                : $payment->paid_at,
            'raw_webhook' => $request->all(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Webhook processed successfully',
            'data' => $payment->fresh(),
        ]);
    }
}