<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\PricingService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __construct(protected PricingService $pricing) {}

    public function index(Request $request)
    {
        $group = $this->pricing->resolveGroup();
        $customerType = $request->get('customer_type', auth()->check() && in_array($group, ['wholesale', 'trading']) ? 'wholesale' : 'retail');

        // Fetch primary parent categories with active children
        $parentCategories = Category::active()
            ->whereNull('parent_id')
            ->with(['children' => fn($q) => $q->active()->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $allCategories = Category::active()->orderBy('sort_order')->get();

        $query = Product::active()->with('category');

        // Availability filter
        $availability = $request->get('availability');
        if ($availability === 'in_stock') {
            $query->inStock();
        } elseif ($availability === 'pre_order') {
            $query->where('is_rfq_only', true);
        }

        // Category & Subcategory Resolution
        $selectedCategorySlug = $request->get('category');
        $selectedSubcategorySlug = $request->get('subcategory');

        if ($selectedSubcategorySlug) {
            $subCat = Category::active()->where('slug', $selectedSubcategorySlug)->first();
            if ($subCat) {
                $query->where('category_id', $subCat->id);
            }
        } elseif ($selectedCategorySlug) {
            $cat = Category::active()->where('slug', $selectedCategorySlug)->with('children')->first();
            if ($cat) {
                if ($cat->children && $cat->children->isNotEmpty()) {
                    $catIds = $cat->children->pluck('id')->push($cat->id)->all();
                    $query->whereIn('category_id', $catIds);
                } else {
                    $query->where('category_id', $cat->id);
                }
            }
        }

        // Search Filter (name, SKU, brand, description, category)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_zh', 'like', "%{$search}%")
                  ->orWhere('name_bm', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('origin', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', "%{$search}%")->orWhere('name_zh', 'like', "%{$search}%"));
            });
        }

        // Origin Filter
        if ($request->filled('origin')) {
            $query->where('origin', $request->origin);
        }

        // Brand Filter
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Pack Size / Weight Filter
        if ($request->filled('pack_size')) {
            $query->where('weight', $request->pack_size);
        }

        // Storage / IQF Filter
        if ($request->filled('storage')) {
            if ($request->storage === 'iqf') {
                $query->where('storage_temp', 'like', '%-18%')->where('name', 'not like', '%Live%');
            } elseif ($request->storage === 'live') {
                $query->where('storage_temp', 'like', '%Live%');
            }
        }

        // Sorting
        $sort = $request->get('sort', 'sort_order');
        if ($sort === 'price_asc') {
            $query->orderBy('retail_price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('retail_price', 'desc');
        } elseif ($sort === 'name') {
            $query->orderBy('name', 'asc');
        } else {
            $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
        }

        $products = $query->paginate(20)->withQueryString();

        // Extract available filter options for dropdowns
        $subcategories = Category::active()->whereNotNull('parent_id')->with('parent')->orderBy('sort_order')->get();
        $availableOrigins = Product::active()->whereNotNull('origin')->where('origin', '!=', '')->distinct()->pluck('origin')->sort()->values();
        $availableBrands = Product::active()->whereNotNull('brand')->where('brand', '!=', '')->distinct()->pluck('brand')->sort()->values();
        $availablePackSizes = Product::active()->whereNotNull('weight')->where('weight', '!=', '')->distinct()->pluck('weight')->sort()->values();

        return view('shop.index', compact(
            'products',
            'parentCategories',
            'subcategories',
            'allCategories',
            'group',
            'customerType',
            'availableOrigins',
            'availableBrands',
            'availablePackSizes'
        ));
    }

    public function categories()
    {
        $group = $this->pricing->resolveGroup();
        $categories = Category::active()
            ->withCount(['products' => function ($q) {
                $q->active();
            }])
            ->orderBy('sort_order')
            ->get();

        return view('categories.index', compact('categories', 'group'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $group  = $this->pricing->resolveGroup();
        $price  = $this->pricing->getPrice($product, $group);
        $moq    = $this->pricing->getMoq($product, $group);

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inStock()
            ->limit(4)
            ->get();

        if ($related->count() < 4) {
            $needed = 4 - $related->count();
            $excludeIds = $related->pluck('id')->push($product->id)->all();
            $fillers = Product::active()
                ->whereNotIn('id', $excludeIds)
                ->inStock()
                ->limit($needed)
                ->get();
            $related = $related->merge($fillers);
        }

        return view('shop.show', compact('product', 'group', 'price', 'moq', 'related'));
    }
}
