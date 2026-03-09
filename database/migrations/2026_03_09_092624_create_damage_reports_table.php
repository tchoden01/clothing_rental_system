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
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('damage_type', ['minor_tear', 'major_tear', 'stain', 'missing_accessory', 'other'])->nullable();
            $table->decimal('damage_fee', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'disputed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_reports');
    }
};
