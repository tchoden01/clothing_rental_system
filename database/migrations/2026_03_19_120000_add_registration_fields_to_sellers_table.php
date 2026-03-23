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
        if (!Schema::hasColumn('sellers', 'full_name')) {
            Schema::table('sellers', function (Blueprint $table) {
                $table->string('full_name')->nullable()->after('user_id');
            });
        }

        if (!Schema::hasColumn('sellers', 'email')) {
            Schema::table('sellers', function (Blueprint $table) {
                $table->string('email')->nullable()->unique()->after('full_name');
            });
        }

        if (!Schema::hasColumn('sellers', 'phone_number')) {
            Schema::table('sellers', function (Blueprint $table) {
                $table->string('phone_number')->nullable()->after('email');
            });
        }

        if (!Schema::hasColumn('sellers', 'password')) {
            Schema::table('sellers', function (Blueprint $table) {
                $table->string('password')->nullable()->after('phone_number');
            });
        }

        if (!Schema::hasColumn('sellers', 'shop_description')) {
            Schema::table('sellers', function (Blueprint $table) {
                $table->text('shop_description')->nullable()->after('shop_name');
            });
        }

        if (!Schema::hasColumn('sellers', 'location')) {
            Schema::table('sellers', function (Blueprint $table) {
                $table->string('location')->nullable()->after('shop_description');
            });
        }

        if (!Schema::hasColumn('sellers', 'cid_number')) {
            Schema::table('sellers', function (Blueprint $table) {
                $table->string('cid_number')->nullable()->unique()->after('location');
            });
        }

        if (!Schema::hasColumn('sellers', 'business_license')) {
            Schema::table('sellers', function (Blueprint $table) {
                $table->string('business_license')->nullable()->after('cid_number');
            });
        }

        if (!Schema::hasColumn('sellers', 'bank_name')) {
            Schema::table('sellers', function (Blueprint $table) {
                $table->string('bank_name')->nullable()->after('business_license');
            });
        }

        if (!Schema::hasColumn('sellers', 'account_number')) {
            Schema::table('sellers', function (Blueprint $table) {
                $table->string('account_number')->nullable()->after('bank_name');
            });
        }

        if (!Schema::hasColumn('sellers', 'account_holder_name')) {
            Schema::table('sellers', function (Blueprint $table) {
                $table->string('account_holder_name')->nullable()->after('account_number');
            });
        }

        if (!Schema::hasColumn('sellers', 'status')) {
            Schema::table('sellers', function (Blueprint $table) {
                $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending')->after('is_verified');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columns = [
            'full_name',
            'email',
            'phone_number',
            'password',
            'shop_description',
            'location',
            'cid_number',
            'business_license',
            'bank_name',
            'account_number',
            'account_holder_name',
            'status',
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn('sellers', $column)) {
                Schema::table('sellers', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
