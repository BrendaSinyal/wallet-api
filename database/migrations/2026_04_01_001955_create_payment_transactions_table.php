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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
           $table->string('invoice_id');
        $table->string('invoice_number');
        $table->string('customer_name')->nullable();
        $table->string('customer_email')->nullable();
        $table->decimal('amount', 15, 2);
        $table->string('currency', 10)->default('NZD');
        $table->string('payment_reference')->unique();
        $table->string('status')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
