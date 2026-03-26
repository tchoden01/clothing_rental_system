<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_amount', 10, 2)->default(0)->after('total_price');
        });

        DB::statement('UPDATE orders SET total_amount = total_price WHERE total_amount = 0');

        Schema::table('order_items', function (Blueprint $table) {
            $table->enum('payout_status', ['pending', 'released'])->default('pending')->after('seller_earnings');
            $table->timestamp('payout_released_at')->nullable()->after('payout_status');
            $table->foreignId('payout_released_by')->nullable()->after('payout_released_at')->constrained('users')->nullOnDelete();
            $table->index('payout_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['payout_released_by']);
            $table->dropIndex(['payout_status']);
            $table->dropColumn(['payout_status', 'payout_released_at', 'payout_released_by']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('total_amount');
        });
    }
};
