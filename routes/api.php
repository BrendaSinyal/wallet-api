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

Route::get('/clear-config', function () {
    try {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

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

Route::get('/seed-api-key', function () {
    $plainKey = 'test_api_key_123';

    \App\Models\ApiKey::updateOrCreate(
        ['name' => 'Default Key'],
        [
            'key_prefix' => substr($plainKey, 0, 8),
            'key_hash' => \Illuminate\Support\Facades\Hash::make($plainKey),
            'is_active' => true,
        ]
    );

    return response()->json([
        'success' => true,
        'api_key' => $plainKey
    ]);
});

Route::get('/debug-routes', function () {
    return \Route::getRoutes()->getRoutesByName();
});