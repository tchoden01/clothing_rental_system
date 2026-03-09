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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->decimal('total_price', 10, 2);
            $table->decimal('platform_commission', 10, 2)->default(0);
            $table->enum('delivery_method', ['pickup', 'home_delivery']);
            $table->text('delivery_address')->nullable();
            $table->date('rental_start_date');
            $table->date('rental_end_date');
            $table->enum('status', ['pending', 'confirmed', 'collected_from_seller', 'picked_up_by_customer', 'in_use', 'returned_by_customer', 'returned_to_seller', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
