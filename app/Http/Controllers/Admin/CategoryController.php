<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct(protected \App\Services\ImageUploadService $imageService) {}

    public function index(Request $request)
    {
        $query = Category::with('parent', 'children')->withCount('products');

        if ($request->filled('q')) {
            $term = trim($request->q);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('slug', 'like', "%{$term}%")
                  ->orWhere('custom_url', 'like', "%{$term}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('featured')) {
            if ($request->featured === 'yes') {
                $query->where('is_featured', true);
            } elseif ($request->featured === 'no') {
                $query->where('is_featured', false);
            }
        }

        if ($request->filled('level')) {
            if ($request->level === 'root') {
                $query->whereNull('parent_id');
            } elseif ($request->level === 'sub') {
                $query->whereNotNull('parent_id');
            }
        }

        $sort = $request->get('sort', 'sort_order');
        $direction = $request->get('direction', 'asc');
        if (in_array($sort, ['name', 'sort_order', 'products_count', 'created_at'])) {
            $query->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('sort_order', 'asc');
        }

        $categories = $query->paginate(20)->withQueryString();

        $stats = [
            'total'    => Category::count(),
            'active'   => Category::where('is_active', true)->count(),
            'featured' => Category::where('is_featured', true)->count(),
            'root'     => Category::whereNull('parent_id')->count(),
        ];

        return view('admin.categories.index', compact('categories', 'stats'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->active()->orderBy('name')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'name_zh'       => 'nullable|string|max:255',
            'name_bm'       => 'nullable|string|max:255',
            'slug'          => 'nullable|string|max:255|unique:categories,slug',
            'custom_url'    => 'nullable|string|max:500',
            'description'   => 'nullable|string',
            'icon'          => 'nullable|string|max:50',
            'parent_id'     => 'nullable|exists:categories,id',
            'sort_order'    => 'integer|min:0',
            'is_active'     => 'nullable|boolean',
            'is_featured'   => 'nullable|boolean',
            'image'         => 'nullable',
            'gallery_image' => 'nullable|string|max:255',
        ]);

        $data['slug'] = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($request->name);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            $media = $this->imageService->upload($request->file('image'), 'categories');
            $data['image'] = $media->path;
        } elseif ($request->filled('gallery_image')) {
            $data['image'] = $request->gallery_image;
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')->where('id', '!=', $category->id)->orderBy('name')->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'name_zh'       => 'nullable|string|max:255',
            'name_bm'       => 'nullable|string|max:255',
            'slug'          => 'nullable|string|max:255',
            'custom_url'    => 'nullable|string|max:500',
            'description'   => 'nullable|string',
            'icon'          => 'nullable|string|max:50',
            'parent_id'     => 'nullable|exists:categories,id',
            'sort_order'    => 'integer|min:0',
            'is_active'     => 'nullable|boolean',
            'is_featured'   => 'nullable|boolean',
            'image'         => 'nullable',
            'gallery_image' => 'nullable|string|max:255',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->filled('slug')) {
            $customSlug = Str::slug($request->slug);
            if (Category::where('slug', $customSlug)->where('id', '!=', $category->id)->exists()) {
                return back()->withInput()->withErrors(['slug' => 'The URL slug "' . $customSlug . '" is already in use by another category.']);
            }
            $data['slug'] = $customSlug;
        } elseif ($request->filled('name') && $request->name !== $category->name) {
            $newSlug = Str::slug($request->name);
            if (!Category::where('slug', $newSlug)->where('id', '!=', $category->id)->exists()) {
                $data['slug'] = $newSlug;
            }
        }

        if ($request->hasFile('image')) {
            $media = $this->imageService->upload($request->file('image'), 'categories');
            $data['image'] = $media->path;
        } elseif ($request->filled('gallery_image')) {
            $data['image'] = $request->gallery_image;
        } elseif ($request->has('gallery_image') && empty($request->gallery_image)) {
            $data['image'] = null;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function toggleFeatured(Category $category)
    {
        $category->update(['is_featured' => !$category->is_featured]);
        $status = $category->is_featured ? 'featured on the homepage' : 'removed from homepage featured list';

        return back()->with('success', "Category '{$category->name}' is now {$status}.");
    }

    public function destroy(Category $category)
    {
        // Safely dissociate child categories and all products (including soft-deleted) before deletion
        Category::where('parent_id', $category->id)->update(['parent_id' => null]);
        Product::withTrashed()->where('category_id', $category->id)->update(['category_id' => null]);

        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', "Category '{$name}' deleted successfully.");
    }
}
