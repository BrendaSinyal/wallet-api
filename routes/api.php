<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\WebhookController;

Route::post('/v1/payments', [PaymentController::class, 'store'])
    ->middleware('api.key');

Route::get('/v1/payments/{reference}', [PaymentController::class, 'show'])
    ->middleware('api.key');

Route::post('/v1/webhooks/payments', [WebhookController::class, 'handle']);

Route::get('/test', function () {
    return response()->json(['ok' => true]);
});

Route::get('/run-migrate', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);

        return response()->json([
            'success' => true,
            'output' => Artisan::output()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});