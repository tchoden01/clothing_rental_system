<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE products MODIFY status ENUM('pending','approved','available','rented','returned','rejected','unavailable') NOT NULL DEFAULT 'pending'");

        DB::table('products')
            ->where('is_approved', false)
            ->where('status', 'available')
            ->update(['status' => 'pending']);

        DB::table('products')
            ->where('is_approved', true)
            ->where('status', 'available')
            ->update(['status' => 'approved']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('products')
            ->whereIn('status', ['pending', 'approved', 'returned', 'rejected'])
            ->update(['status' => 'available']);

        DB::statement("ALTER TABLE products MODIFY status ENUM('available','rented','unavailable') NOT NULL DEFAULT 'available'");
    }
};
