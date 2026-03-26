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
        Schema::table('products', function (Blueprint $table) {
            $table->enum('gender', ['men', 'women', 'kids', 'unisex'])
                ->default('unisex')
                ->after('category_id');
            $table->enum('kid_type', ['boys', 'girls'])
                ->nullable()
                ->after('gender');
        });

        if (Schema::hasColumn('products', 'for')) {
            DB::statement('UPDATE products SET gender = `for` WHERE `for` IN ("men", "women", "kids", "unisex")');
            DB::statement('UPDATE products SET gender = "unisex" WHERE gender IS NULL OR gender = ""');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['gender', 'kid_type']);
        });
    }
};
