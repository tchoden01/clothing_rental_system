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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Section 1: Account Info
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('password');

            // Section 2: Shop Info
            $table->string('shop_name');
            $table->text('shop_description');
            $table->string('location');

            // Section 3: Verification
            $table->string('cid_number')->unique();
            $table->string('business_license');

            // Section 4: Payment Info
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_holder_name');

            // Legacy compatibility fields
            $table->string('contact_number');
            $table->text('address');
            $table->boolean('is_verified')->default(false);
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
