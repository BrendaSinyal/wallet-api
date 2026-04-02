<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
        protected $fillable = [
        'invoice_id',
        'invoice_number',
        'customer_name',
        'customer_email',
        'amount',
        'currency',
        'payment_reference',
        'payment_url',
        'external_transaction_id',
        'status',
        'paid_at',
        'raw_webhook',
        'wallet_checkout_url',
        'payment_method',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'raw_webhook' => 'array',
    ];
}
