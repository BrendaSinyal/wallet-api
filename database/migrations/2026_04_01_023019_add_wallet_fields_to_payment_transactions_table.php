<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->text('wallet_checkout_url')->nullable()->after('payment_url');
            $table->string('payment_method')->nullable()->after('wallet_checkout_url');
        });
    }

    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'wallet_checkout_url',
                'payment_method',
            ]);
        });
    }
};