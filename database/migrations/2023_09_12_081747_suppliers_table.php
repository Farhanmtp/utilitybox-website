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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('powwr_id');
            $table->string('name', 255);
            $table->string('slug', 255)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('supplier_type', 10)->nullable();
            $table->text('uplifts')->nullable();
            $table->longText('plans')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
