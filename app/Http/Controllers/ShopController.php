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

        $categories = Category::active()->orderBy('sort_order')->get();

        $query = Product::active()->inStock()->with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $sort = $request->get('sort', 'sort_order');
        $query->orderBy($sort === 'price_asc' ? "retail_price" : ($sort === 'price_desc' ? "retail_price" : 'sort_order'),
                        $sort === 'price_desc' ? 'desc' : 'asc');

        $products = $query->paginate(16)->withQueryString();

        return view('shop.index', compact('products', 'categories', 'group'));
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
