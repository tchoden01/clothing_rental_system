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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->string('condition')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->decimal('rental_price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->json('images')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->enum('status', ['available', 'rented', 'unavailable'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
