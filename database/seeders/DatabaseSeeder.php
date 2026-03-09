<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
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

        // Create Sample Categories
        $categories = [
            ['name' => 'Traditional Gho', 'description' => 'Traditional Bhutanese men\'s clothing'],
            ['name' => 'Traditional Kira', 'description' => 'Traditional Bhutanese women\'s clothing'],
            ['name' => 'Ceremonial Wear', 'description' => 'Special occasion traditional wear'],
            ['name' => 'Wedding Attire', 'description' => 'Traditional wedding clothing'],
            ['name' => 'Festival Wear', 'description' => 'Clothing for festivals and celebrations'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

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


