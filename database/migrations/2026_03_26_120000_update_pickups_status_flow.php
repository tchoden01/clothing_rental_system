<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('pickups')) {
            return;
        }

        DB::table('pickups')->where('pickup_status', 'pending_pickup')->update(['pickup_status' => 'pending']);
        DB::table('pickups')->where('pickup_status', 'scheduled')->update(['pickup_status' => 'ready']);
        DB::table('pickups')->where('pickup_status', 'picked_up_from_seller')->update(['pickup_status' => 'picked_up']);
        DB::table('pickups')->where('pickup_status', 'delivered_to_customer')->update(['pickup_status' => 'in_use']);
        DB::table('pickups')->where('pickup_status', 'return_pickup_scheduled')->update(['pickup_status' => 'in_use']);
        DB::table('pickups')->where('pickup_status', 'returned_from_customer')->update(['pickup_status' => 'returned']);

        DB::statement("ALTER TABLE pickups MODIFY pickup_status ENUM('pending','ready','picked_up','in_use','returned','completed') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('pickups')) {
            return;
        }

        DB::table('pickups')->where('pickup_status', 'pending')->update(['pickup_status' => 'pending_pickup']);
        DB::table('pickups')->where('pickup_status', 'ready')->update(['pickup_status' => 'scheduled']);
        DB::table('pickups')->where('pickup_status', 'picked_up')->update(['pickup_status' => 'picked_up_from_seller']);
        DB::table('pickups')->where('pickup_status', 'in_use')->update(['pickup_status' => 'delivered_to_customer']);
        DB::table('pickups')->where('pickup_status', 'returned')->update(['pickup_status' => 'returned_from_customer']);

        DB::statement("ALTER TABLE pickups MODIFY pickup_status VARCHAR(255) NOT NULL DEFAULT 'pending_pickup'");
    }
};
