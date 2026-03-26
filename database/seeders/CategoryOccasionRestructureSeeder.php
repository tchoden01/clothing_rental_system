<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Occasion;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryOccasionRestructureSeeder extends Seeder
{
    /**
     * Remap legacy mixed categories into category hierarchy + occasion tags.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $traditionalWear = Category::firstOrCreate(
                ['name' => 'Traditional Wear', 'parent_id' => null],
                ['description' => 'Traditional garments and heritage pieces.']
            );

            $modernWear = Category::firstOrCreate(
                ['name' => 'Modern Wear', 'parent_id' => null],
                ['description' => 'Contemporary clothing styles.']
            );

            $accessories = Category::firstOrCreate(
                ['name' => 'Accessories', 'parent_id' => null],
                ['description' => 'Traditional accessories and add-ons.']
            );

            $gho = Category::firstOrCreate(
                ['name' => 'Gho', 'parent_id' => $traditionalWear->id],
                ['description' => 'Traditional Bhutanese attire for men.']
            );

            $kira = Category::firstOrCreate(
                ['name' => 'Kira', 'parent_id' => $traditionalWear->id],
                ['description' => 'Traditional Bhutanese attire for women.']
            );

            $dresses = Category::firstOrCreate(
                ['name' => 'Dresses', 'parent_id' => $modernWear->id],
                ['description' => 'Modern dresses in various styles.']
            );

            $suits = Category::firstOrCreate(
                ['name' => 'Suits', 'parent_id' => $modernWear->id],
                ['description' => 'Formal and semi-formal suits.']
            );

            $kera = Category::firstOrCreate(
                ['name' => 'Kera', 'parent_id' => $accessories->id],
                ['description' => 'Traditional belt accessory.']
            );

            $kabney = Category::firstOrCreate(
                ['name' => 'Kabney', 'parent_id' => $accessories->id],
                ['description' => 'Traditional ceremonial scarf for men.']
            );

            $rachu = Category::firstOrCreate(
                ['name' => 'Rachu', 'parent_id' => $accessories->id],
                ['description' => 'Traditional ceremonial scarf for women.']
            );

            $wedding = Occasion::firstOrCreate(['name' => 'Wedding']);
            $festival = Occasion::firstOrCreate(['name' => 'Festival']);
            $casual = Occasion::firstOrCreate(['name' => 'Casual']);
            $formal = Occasion::firstOrCreate(['name' => 'Formal']);
            $ceremonial = Occasion::firstOrCreate(['name' => 'Ceremonial']);

            // Keep core tags available even if some are not mapped from legacy names.
            $occasionIds = [
                'Wedding' => $wedding->id,
                'Festival' => $festival->id,
                'Casual' => $casual->id,
                'Formal' => $formal->id,
                'Ceremonial' => $ceremonial->id,
            ];

            $legacyMap = [
                'Traditional Gho' => [
                    'category_id' => $gho->id,
                    'occasion_names' => [],
                ],
                'Traditional Kira' => [
                    'category_id' => $kira->id,
                    'occasion_names' => [],
                ],
                'Wedding Attire' => [
                    'category_id' => $dresses->id,
                    'occasion_names' => ['Wedding'],
                ],
                'Ceremonial Wear' => [
                    'category_id' => $suits->id,
                    'occasion_names' => ['Ceremonial'],
                ],
                'Festival Wear' => [
                    'category_id' => $dresses->id,
                    'occasion_names' => ['Festival'],
                ],
                'Others' => [
                    'category_id' => $dresses->id,
                    'occasion_names' => ['Casual'],
                ],
                'Accessories' => [
                    'category_id' => $kera->id,
                    'occasion_names' => [],
                ],
            ];

            foreach ($legacyMap as $legacyName => $target) {
                $legacyCategory = Category::where('name', $legacyName)->first();

                if (!$legacyCategory) {
                    continue;
                }

                $products = Product::where('category_id', $legacyCategory->id)->get();

                foreach ($products as $product) {
                    $product->update(['category_id' => $target['category_id']]);

                    $ids = array_values(array_map(
                        fn (string $occasionName) => $occasionIds[$occasionName],
                        $target['occasion_names']
                    ));

                    if (!empty($ids)) {
                        $product->occasions()->syncWithoutDetaching($ids);
                    }
                }

                // Remove the old mixed category once products are remapped.
                $legacyCategory->delete();
            }
        });
    }
}
