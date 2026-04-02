<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\PaymentPageController;

Route::get('/pay/{reference}', [PaymentPageController::class, 'show']);
Route::post('/pay/{reference}/checkout', [PaymentPageController::class, 'checkout']);



