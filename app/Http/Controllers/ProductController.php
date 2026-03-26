<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    // Display all approved products
    public function index(Request $request)
    {
        $query = Product::with(['seller', 'category'])
            ->where('is_approved', true)
            ->where('status', '!=', 'rejected');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter (supports both single value and category[] input)
        $categoryFilter = $request->input('category');
        if (!is_null($categoryFilter)) {
            $categoryValues = is_array($categoryFilter) ? $categoryFilter : [$categoryFilter];
            $categoryValues = array_values(array_filter($categoryValues, function ($value) {
                return $value !== null && $value !== '';
            }));

            if (!empty($categoryValues)) {
                $numericCategoryIds = array_values(array_filter($categoryValues, 'is_numeric'));
                $categoryNames = array_values(array_filter($categoryValues, function ($value) {
                    return !is_numeric($value);
                }));

                if (!empty($numericCategoryIds)) {
                    $query->whereIn('category_id', $numericCategoryIds);
                }

                if (!empty($categoryNames)) {
                    $query->whereHas('category', function($q) use ($categoryNames) {
                        $q->where(function ($nameQuery) use ($categoryNames) {
                            foreach ($categoryNames as $categoryName) {
                                $nameQuery->orWhere('name', 'like', '%' . $categoryName . '%');
                            }
                        });
                    });
                }
            }
        }

        // Size filter
        $sizeFilter = $request->input('size');
        if (!is_null($sizeFilter)) {
            $sizeValues = is_array($sizeFilter) ? $sizeFilter : [$sizeFilter];
            $sizeValues = array_values(array_filter($sizeValues, function ($value) {
                return $value !== null && $value !== '';
            }));

            if (!empty($sizeValues)) {
                $query->whereIn('size', $sizeValues);
            }
        }

        // Color filter
        $colorFilter = $request->input('color');
        if (!is_null($colorFilter)) {
            $colorValues = is_array($colorFilter) ? $colorFilter : [$colorFilter];
            $colorValues = array_values(array_filter($colorValues, function ($value) {
                return $value !== null && $value !== '';
            }));

            if (!empty($colorValues)) {
                $query->whereIn('color', $colorValues);
            }
        }

        // Audience filter (For)
        $forFilter = $request->input('for');
        if (!is_null($forFilter)) {
            $forValues = is_array($forFilter) ? $forFilter : [$forFilter];
            $forValues = array_values(array_filter($forValues, function ($value) {
                return in_array($value, ['men', 'women', 'kids', 'unisex'], true);
            }));

            if (!empty($forValues)) {
                $query->whereIn('gender', $forValues);
            }
        }

        // Occasion filter (applies only when the products table has the column)
        $occasionFilter = $request->input('occasion');
        if (!is_null($occasionFilter) && Schema::hasColumn('products', 'occasion')) {
            $occasionValues = is_array($occasionFilter) ? $occasionFilter : [$occasionFilter];
            $occasionValues = array_values(array_filter($occasionValues, function ($value) {
                return $value !== null && $value !== '';
            }));

            if (!empty($occasionValues)) {
                $query->whereIn('occasion', $occasionValues);
            }
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('rental_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('rental_price', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);

        $activeRentalItems = $this->getActiveRentalItemsForProductIds(
            $products->getCollection()->pluck('id')->all()
        );

        $products->setCollection(
            $products->getCollection()->map(function (Product $product) use ($activeRentalItems) {
                $this->attachAvailabilityMeta($product, $activeRentalItems[$product->id] ?? null);
                return $product;
            })
        );

        $categories = Category::where('is_approved', true)
            ->whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->where('is_approved', true)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        return view('products.index', compact('products', 'categories'));
    }

    // Show single product details
    public function show($id)
    {
        $product = Product::with(['seller.user', 'category'])
            ->where('is_approved', true)
            ->where('status', '!=', 'rejected')
            ->findOrFail($id);

        $activeRentalItem = $this->getActiveRentalItemsForProductIds([$product->id])->get($product->id);
        $this->attachAvailabilityMeta($product, $activeRentalItem);

        return view('products.show', compact('product'));
    }

    // Search products
    public function search(Request $request)
    {
        $search = $request->get('query');
        
        $products = Product::with(['seller', 'category'])
            ->where('is_approved', true)
            ->where('status', '!=', 'rejected')
            ->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });

        $forFilter = $request->input('for');
        if (!is_null($forFilter)) {
            $forValues = is_array($forFilter) ? $forFilter : [$forFilter];
            $forValues = array_values(array_filter($forValues, function ($value) {
                return in_array($value, ['men', 'women', 'kids', 'unisex'], true);
            }));

            if (!empty($forValues)) {
                $products->whereIn('gender', $forValues);
            }
        }

        $products = $products
            ->paginate(12);

        $activeRentalItems = $this->getActiveRentalItemsForProductIds(
            $products->getCollection()->pluck('id')->all()
        );

        $products->setCollection(
            $products->getCollection()->map(function (Product $product) use ($activeRentalItems) {
                $this->attachAvailabilityMeta($product, $activeRentalItems[$product->id] ?? null);
                return $product;
            })
        );

        $categories = Category::where('is_approved', true)
            ->whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->where('is_approved', true)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        return view('products.index', compact('products', 'categories', 'search'));
    }

    private function getActiveRentalItemsForProductIds(array $productIds)
    {
        if (empty($productIds)) {
            return collect();
        }

        $activeOrderStatuses = ['ongoing', 'confirmed', 'collected_from_seller', 'picked_up_by_customer', 'in_use'];

        return OrderItem::with('order:id,status')
            ->whereIn('product_id', $productIds)
            ->whereHas('order', function ($query) use ($activeOrderStatuses) {
                $query->whereIn('status', $activeOrderStatuses);
            })
            ->orderBy('rental_end_date', 'desc')
            ->get()
            ->groupBy('product_id')
            ->map(function ($items) {
                return $items->first();
            });
    }

    private function attachAvailabilityMeta(Product $product, ?OrderItem $activeRentalItem): void
    {
        $isCurrentlyRented = false;
        $returnDate = null;

        if ($activeRentalItem && $activeRentalItem->rental_end_date) {
            $returnDate = Carbon::parse($activeRentalItem->rental_end_date);
            $isCurrentlyRented = $returnDate->endOfDay()->gte(now());
        }

        $product->is_currently_rented = $isCurrentlyRented;
        $product->return_date = $returnDate;
        $product->is_returning_soon = $isCurrentlyRented
            && $returnDate
            && now()->diffInDays($returnDate, false) <= 3;
        $product->is_rentable_now = !$isCurrentlyRented && (int) $product->quantity > 0;
    }
}
