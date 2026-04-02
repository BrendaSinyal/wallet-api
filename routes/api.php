<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\WebhookController;

Route::post('/v1/payments', [PaymentController::class, 'store']);
Route::get('/v1/payments/{reference}', [PaymentController::class, 'show']);
Route::post('/v1/webhooks/payments', [WebhookController::class, 'handle']);

Route::get('/test', function () {
    return response()->json(['ok' => true]);
});

Route::middleware([])->get('/test', function () {
    return response()->json(['ok' => true]);
});