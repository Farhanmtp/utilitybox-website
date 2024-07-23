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
        Schema::create('powwr_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('powwr_id');
            $table->string('name', 255);
            $table->string('logo', 255);
            $table->string('supplier_type', 10)->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('powwr_suppliers');
    }
};
