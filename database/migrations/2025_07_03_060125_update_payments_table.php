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
        Schema::table('payments', function (Blueprint $table) {
            // Add payment_method column if it doesn't exist
            if (!Schema::hasColumn('payments', 'payment_method')) {
                $table->string('payment_method')->default('bank_transfer')->after('id');
            }

            // Make sure other columns are properly configured
            if (!Schema::hasColumn('payments', 'external_id')) {
                $table->string('external_id')->nullable()->after('transaction_id');
            }

            if (!Schema::hasColumn('payments', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'external_id', 'paid_at']);
        });
    }
};
