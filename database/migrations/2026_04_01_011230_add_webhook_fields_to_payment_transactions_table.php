<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->string('external_transaction_id')->nullable()->after('payment_reference');
            $table->timestamp('paid_at')->nullable()->after('status');
            $table->json('raw_webhook')->nullable()->after('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'external_transaction_id',
                'paid_at',
                'raw_webhook',
            ]);
        });
    }
};
