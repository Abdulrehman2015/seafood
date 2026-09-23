<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct(protected \App\Services\ImageUploadService $imageService) {}

    public function index(Request $request)
    {
        $query = Product::with('category')->withTrashed();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)->whereNull('deleted_at');
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false)->whereNull('deleted_at');
            } elseif ($request->status === 'archived') {
                $query->onlyTrashed();
            }
        }

        if ($request->filled('stock')) {
            if ($request->stock === 'in_stock') {
                $query->where(function ($q) {
                    $q->where('track_stock', false)->orWhere('stock_quantity', '>', 5);
                });
            } elseif ($request->stock === 'low_stock') {
                $query->where('track_stock', true)->where('stock_quantity', '<=', 5)->where('stock_quantity', '>', 0);
            } elseif ($request->stock === 'out_of_stock') {
                $query->where('track_stock', true)->where('stock_quantity', '<=', 0);
            }
        }

        $products   = $query->orderBy('sort_order')->paginate(20)->withQueryString();
        $categories = Category::active()->orderBy('name')->get();

        $stats = [
            'total'        => Product::withTrashed()->count(),
            'active'       => Product::where('is_active', true)->whereNull('deleted_at')->count(),
            'low_stock'    => Product::where('track_stock', true)->where('stock_quantity', '<=', 5)->where('stock_quantity', '>', 0)->whereNull('deleted_at')->count(),
            'out_of_stock' => Product::where('track_stock', true)->where('stock_quantity', '<=', 0)->whereNull('deleted_at')->count(),
            'archived'     => Product::onlyTrashed()->count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        // Explicitly set boolean toggle switches (unchecked checkboxes are omitted in POST requests)
        $validated['is_active']           = $request->boolean('is_active');
        $validated['is_walkin_available'] = $request->boolean('is_walkin_available');
        $validated['is_featured']        = $request->boolean('is_featured');
        $validated['is_rfq_only']        = $request->boolean('is_rfq_only');
        $validated['track_stock']         = $request->boolean('track_stock');

        // Handle thumbnail upload or gallery selection
        if ($request->hasFile('thumbnail')) {
            $media = $this->imageService->upload($request->file('thumbnail'), 'products');
            $validated['thumbnail'] = $media->path;
        } elseif ($request->filled('gallery_thumbnail')) {
            $validated['thumbnail'] = $request->gallery_thumbnail;
        }

        // Handle multiple images (files + gallery items)
        $images = [];
        if ($request->filled('gallery_images')) {
            $galleryImages = is_array($request->gallery_images) ? $request->gallery_images : explode(',', $request->gallery_images);
            $images = array_merge($images, array_filter($galleryImages));
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $media = $this->imageService->upload($img, 'products');
                $images[] = $media->path;
            }
        }
        $validated['images'] = array_values(array_unique(array_filter($images)));

        // Handle specifications as JSON
        if ($request->filled('spec_keys')) {
            $specs = [];
            foreach ($request->spec_keys as $i => $key) {
                if ($key) {
                    $specs[$key] = $request->spec_values[$i] ?? '';
                }
            }
            $validated['specifications'] = $specs;
        }

        $validated['slug'] = Str::slug($validated['name']);

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product->id);

        // Explicitly set boolean toggle switches (unchecked checkboxes are omitted in POST requests)
        $validated['is_active']           = $request->boolean('is_active');
        $validated['is_walkin_available'] = $request->boolean('is_walkin_available');
        $validated['is_featured']        = $request->boolean('is_featured');
        $validated['is_rfq_only']        = $request->boolean('is_rfq_only');
        $validated['track_stock']         = $request->boolean('track_stock');

        if ($request->hasFile('thumbnail')) {
            $media = $this->imageService->upload($request->file('thumbnail'), 'products');
            $validated['thumbnail'] = $media->path;
        } elseif ($request->filled('gallery_thumbnail')) {
            $validated['thumbnail'] = $request->gallery_thumbnail;
        }

        $images = $request->input('existing_images', $product->images ?? []);
        if (!is_array($images)) $images = [];

        if ($request->filled('gallery_images')) {
            $galleryImages = is_array($request->gallery_images) ? $request->gallery_images : explode(',', $request->gallery_images);
            $images = array_merge($images, array_filter($galleryImages));
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $media = $this->imageService->upload($img, 'products');
                $images[] = $media->path;
            }
        }
        $validated['images'] = array_values(array_unique(array_filter($images)));

        if ($request->filled('spec_keys')) {
            $specs = [];
            foreach ($request->spec_keys as $i => $key) {
                if ($key) {
                    $specs[$key] = $request->spec_values[$i] ?? '';
                }
            }
            $validated['specifications'] = $specs;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function restore(int $id)
    {
        Product::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Product restored.');
    }

    protected function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'                 => 'required|string|max:255',
            'name_zh'              => 'nullable|string|max:255',
            'name_bm'              => 'nullable|string|max:255',
            'sku'                  => 'nullable|string|max:100|unique:products,sku,' . ($ignoreId ?? 'NULL'),
            'category_id'          => 'nullable|exists:categories,id',
            'short_description'    => 'nullable|string|max:500',
            'short_description_zh' => 'nullable|string|max:500',
            'short_description_bm' => 'nullable|string|max:500',
            'description'          => 'nullable|string',
            'description_zh'       => 'nullable|string',
            'description_bm'       => 'nullable|string',
            'retail_price'       => 'required|numeric|min:0',
            'walkin_price'       => 'required|numeric|min:0',
            'wholesale_price'    => 'required|numeric|min:0',
            'trading_price'      => 'nullable|numeric|min:0',
            'price_sgd'          => 'nullable|numeric|min:0',
            'price_usd'          => 'nullable|numeric|min:0',
            'wholesale_price_sgd'  => 'nullable|numeric|min:0',
            'wholesale_price_usd'  => 'nullable|numeric|min:0',
            'trading_price_sgd'   => 'nullable|numeric|min:0',
            'trading_price_usd'   => 'nullable|numeric|min:0',
            'weight'             => 'nullable|string|max:50',
            'unit'               => 'required|string|max:20',
            'origin'             => 'nullable|string|max:100',
            'storage_temp'       => 'nullable|string|max:50',
            'storage_icon'       => 'nullable|string|max:20',
            'brand'              => 'nullable|string|max:100',
            'stock_quantity'     => 'required|integer|min:0',
            'track_stock'        => 'nullable|boolean',
            'moq'                => 'required|integer|min:1',
            'moq_wholesale'      => 'required|integer|min:1',
            'moq_trading'        => 'required|integer|min:1',
            'is_active'          => 'nullable|boolean',
            'is_walkin_available'=> 'nullable|boolean',
            'is_featured'        => 'nullable|boolean',
            'is_rfq_only'        => 'nullable|boolean',
            'sort_order'         => 'integer|min:0',
            'thumbnail'          => 'nullable',
            'gallery_thumbnail'  => 'nullable|string',
            'gallery_images'     => 'nullable',
            'images.*'           => 'nullable',
        ]);
    }
}
