<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('powwr_deals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('dealId', 255)->nullable();
            $table->string('envelopeId', 255)->nullable();
            $table->string('loaEnvelopeId', 255)->nullable();
            $table->string('supplierId')->nullable();
            $table->string('utilityType')->nullable();
            $table->string('meterNumber')->nullable();
            $table->string('step')->nullable();
            $table->string('tab')->nullable();
            $table->text('customer')->nullable();
            $table->text('company')->nullable();
            $table->text('site')->nullable();
            $table->text('contract')->nullable();
            $table->text('smeDetails')->nullable();
            $table->text('billingAddress')->nullable();
            $table->text('paymentDetail')->nullable();
            $table->text('bankDetails')->nullable();
            $table->text('bankAddress')->nullable();
            $table->text('quoteDetails')->nullable();
            $table->text('consents')->nullable();
            $table->text('rates')->nullable();
            $table->text('usage')->nullable();
            $table->string('status', 100)->default('pending')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('powwr_deals');
    }
};