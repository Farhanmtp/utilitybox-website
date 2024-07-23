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
        Schema::table('powwr_deals', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('meterNumber');
            $table->string('customer_email')->nullable()->after('customer_name');
            $table->string('customer_phone')->nullable()->after('customer_email');

            if (!Schema::hasColumn('powwr_deals', 'status')) {
                $table->string('status', 100)->default('pending')->nullable()->after('usage');
            }
            $table->timestamp('link_sent_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('powwr_deals', function (Blueprint $table) {
            $table->removeColumn('link_sent_at');
        });
    }
};
