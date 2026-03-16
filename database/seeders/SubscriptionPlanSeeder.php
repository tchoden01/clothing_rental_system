<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => '4-Item Plan',
                'slug' => 'pick-me-up',
                'description' => 'Rent 4 items at a time. Swap every 30 days.',
                'item_limit' => 4,
                'price' => 89.00,
                'first_month_price' => null,
                'swap_days' => 30,
                'is_active' => true,
            ],
            [
                'name' => '7-Item Plan',
                'slug' => 'enhancer',
                'description' => 'Rent 7 items at a time. Swap every 30 days.',
                'item_limit' => 7,
                'price' => 129.00,
                'first_month_price' => 99.00,
                'swap_days' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Unlimited Plan',
                'slug' => 'replacer',
                'description' => 'Rent 6 items at a time. Unlimited Swaps.',
                'item_limit' => -1, // -1 indicates unlimited
                'price' => 249.00,
                'first_month_price' => 99.00,
                'swap_days' => 0, // Swap anytime
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
