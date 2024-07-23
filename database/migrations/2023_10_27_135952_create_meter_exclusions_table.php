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
        Schema::create('meter_exclusions', function (Blueprint $table) {
            $table->id();
            $table->string('utility_type')->nullable();
            $table->string('meter_number')->nullable();
            $table->string('mpan_top')->nullable();
            $table->string('mpr')->nullable();
            $table->string('serial_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meter_exclusions');
    }
};
