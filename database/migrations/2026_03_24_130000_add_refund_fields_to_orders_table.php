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
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('cancelled_at')->nullable()->after('status');
            $table->foreignId('cancelled_by')->nullable()->after('cancelled_at')->constrained('users')->nullOnDelete();
            $table->unsignedInteger('cancellation_hours_before_start')->nullable()->after('cancelled_by');
            $table->decimal('refundable_base_amount', 10, 2)->default(0)->after('cancellation_hours_before_start');
            $table->decimal('refund_percentage', 5, 2)->default(0)->after('refundable_base_amount');
            $table->decimal('refund_amount', 10, 2)->default(0)->after('refund_percentage');
            $table->decimal('platform_fee_amount', 10, 2)->default(0)->after('refund_amount');
            $table->boolean('platform_fee_refunded')->default(false)->after('platform_fee_amount');
            $table->timestamp('refund_processed_at')->nullable()->after('platform_fee_refunded');
            $table->boolean('is_refund_overridden')->default(false)->after('refund_processed_at');
            $table->foreignId('refund_overridden_by')->nullable()->after('is_refund_overridden')->constrained('users')->nullOnDelete();
            $table->timestamp('refund_override_at')->nullable()->after('refund_overridden_by');
            $table->text('refund_override_note')->nullable()->after('refund_override_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['cancelled_by']);
            $table->dropForeign(['refund_overridden_by']);
            $table->dropColumn([
                'cancelled_at',
                'cancelled_by',
                'cancellation_hours_before_start',
                'refundable_base_amount',
                'refund_percentage',
                'refund_amount',
                'platform_fee_amount',
                'platform_fee_refunded',
                'refund_processed_at',
                'is_refund_overridden',
                'refund_overridden_by',
                'refund_override_at',
                'refund_override_note',
            ]);
        });
    }
};
