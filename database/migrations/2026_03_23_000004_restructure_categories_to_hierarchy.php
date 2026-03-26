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
        DB::transaction(function () {
            $traditionalWearId = $this->upsertCategory('Traditional Wear', null, 'Traditional garments and heritage pieces.');
            $modernWearId = $this->upsertCategory('Modern Wear', null, 'Contemporary clothing styles.');
            $accessoriesId = $this->upsertCategory('Accessories', null, 'Traditional accessories and add-ons.');

            $ghoId = $this->upsertCategory('Gho', $traditionalWearId, 'Traditional Bhutanese attire for men.');
            $kiraId = $this->upsertCategory('Kira', $traditionalWearId, 'Traditional Bhutanese attire for women.');
            $dressesId = $this->upsertCategory('Dresses', $modernWearId, 'Modern dresses in various styles.');
            $suitsId = $this->upsertCategory('Suits', $modernWearId, 'Formal and semi-formal suits.');
            $keraId = $this->upsertCategory('Kera', $accessoriesId, 'Traditional belt accessory.');
            $kabneyId = $this->upsertCategory('Kabney', $accessoriesId, 'Traditional ceremonial scarf for men.');
            $rachuId = $this->upsertCategory('Rachu', $accessoriesId, 'Traditional ceremonial scarf for women.');

            $this->remapFlatCategoryToHierarchy('Gho', $ghoId, $traditionalWearId);
            $this->remapFlatCategoryToHierarchy('Kira', $kiraId, $traditionalWearId);
            $this->remapFlatCategoryToHierarchy('Dresses', $dressesId, $modernWearId);
            $this->remapFlatCategoryToHierarchy('Suits', $suitsId, $modernWearId);

            $others = DB::table('categories')
                ->where('name', 'Others')
                ->whereNull('parent_id')
                ->first();

            if ($others) {
                $this->moveProductsByKeyword((int) $others->id, $suitsId, ['suit', 'blazer', 'tux', 'coat', 'formal']);

                DB::table('products')
                    ->where('category_id', $others->id)
                    ->update(['category_id' => $dressesId]);

                DB::table('categories')->where('id', $others->id)->delete();
            }

            $this->moveProductsByKeyword($accessoriesId, $kabneyId, ['kabney']);
            $this->moveProductsByKeyword($accessoriesId, $rachuId, ['rachu']);
            $this->moveProductsByKeyword($accessoriesId, $keraId, ['kera']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data migration is intentionally non-reversible.
    }

    private function upsertCategory(string $name, ?int $parentId, ?string $description = null): int
    {
        $query = DB::table('categories')->where('name', $name);
        $parentId === null ? $query->whereNull('parent_id') : $query->where('parent_id', $parentId);

        $existing = $query->first();

        if ($existing) {
            DB::table('categories')
                ->where('id', $existing->id)
                ->update([
                    'description' => $description,
                    'is_approved' => true,
                    'updated_at' => now(),
                ]);

            return (int) $existing->id;
        }

        return (int) DB::table('categories')->insertGetId([
            'name' => $name,
            'parent_id' => $parentId,
            'description' => $description,
            'seller_id' => null,
            'is_approved' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function remapFlatCategoryToHierarchy(string $name, int $targetId, int $expectedParentId): void
    {
        $flatCategory = DB::table('categories')
            ->where('name', $name)
            ->whereNull('parent_id')
            ->where('id', '!=', $expectedParentId)
            ->where('id', '!=', $targetId)
            ->first();

        if (!$flatCategory) {
            return;
        }

        DB::table('products')
            ->where('category_id', $flatCategory->id)
            ->update(['category_id' => $targetId]);

        DB::table('categories')->where('id', $flatCategory->id)->delete();
    }

    private function moveProductsByKeyword(int $sourceCategoryId, int $targetCategoryId, array $keywords): void
    {
        foreach ($keywords as $keyword) {
            $like = '%' . strtolower($keyword) . '%';

            DB::table('products')
                ->where('category_id', $sourceCategoryId)
                ->where(function ($query) use ($like) {
                    $query->whereRaw('LOWER(name) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(description) LIKE ?', [$like]);
                })
                ->update(['category_id' => $targetCategoryId]);
        }
    }
};
