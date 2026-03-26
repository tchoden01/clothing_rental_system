<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Occasion;
use App\Models\PlatformSetting;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@clothing.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'contact_number' => '1234567890',
            'address' => 'Admin Office',
        ]);

        // Create category hierarchy where categories represent clothing type only.
        $traditionalWear = Category::create([
            'name' => 'Traditional Wear',
            'description' => 'Traditional garments and heritage pieces.',
        ]);

        $modernWear = Category::create([
            'name' => 'Modern Wear',
            'description' => 'Contemporary clothing styles.',
        ]);

        $accessories = Category::create([
            'name' => 'Accessories',
            'description' => 'Traditional accessories and add-ons.',
        ]);

        Category::insert([
            [
                'name' => 'Gho',
                'parent_id' => $traditionalWear->id,
                'description' => 'Traditional Bhutanese attire for men.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kira',
                'parent_id' => $traditionalWear->id,
                'description' => 'Traditional Bhutanese attire for women.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dresses',
                'parent_id' => $modernWear->id,
                'description' => 'Modern dresses in various styles.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Suits',
                'parent_id' => $modernWear->id,
                'description' => 'Formal and semi-formal suits.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kera',
                'parent_id' => $accessories->id,
                'description' => 'Traditional belt accessory.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kabney',
                'parent_id' => $accessories->id,
                'description' => 'Traditional ceremonial scarf for men.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rachu',
                'parent_id' => $accessories->id,
                'description' => 'Traditional ceremonial scarf for women.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Create reusable occasion tags.
        Occasion::insert([
            ['name' => 'Wedding', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Festival', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Casual', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Formal', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ceremonial', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Create Platform Settings
        PlatformSetting::create([
            'key' => 'commission_rate',
            'value' => '15',
            'description' => 'Platform commission rate in percentage',
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin login: admin@clothing.com / admin123');
    }
}


