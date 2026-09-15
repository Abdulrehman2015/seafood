<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function __construct(protected ImageUploadService $imageService) {}

    public function index(Request $request)
    {
        $query = Review::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->boolean('featured'));
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('role_or_company', 'like', "%{$s}%")
                  ->orWhere('comment', 'like', "%{$s}%");
            });
        }

        $reviews = $query->orderBy('sort_order', 'asc')->latest()->paginate(15)->withQueryString();

        $stats = [
            'total'    => Review::count(),
            'approved' => Review::where('status', 'approved')->count(),
            'pending'  => Review::where('status', 'pending')->count(),
            'featured' => Review::where('is_featured', true)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    public function create()
    {
        return view('admin.reviews.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:200',
            'role_or_company' => 'nullable|string|max:200',
            'rating'          => 'required|integer|min:1|max:5',
            'comment'         => 'required|string|max:2000',
            'avatar'          => 'nullable|image|max:2048',
            'is_featured'     => 'boolean',
            'status'          => 'required|in:approved,pending',
            'sort_order'      => 'integer|min:0',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('avatar')) {
            $media = $this->imageService->upload($request->file('avatar'), 'reviews', $request->name);
            $data['avatar'] = $media->path;
        }

        Review::create($data);

        return redirect()->route('admin.reviews.index')->with('success', 'Customer review created successfully.');
    }

    public function edit(Review $review)
    {
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:200',
            'role_or_company' => 'nullable|string|max:200',
            'rating'          => 'required|integer|min:1|max:5',
            'comment'         => 'required|string|max:2000',
            'avatar'          => 'nullable|image|max:2048',
            'is_featured'     => 'boolean',
            'status'          => 'required|in:approved,pending',
            'sort_order'      => 'integer|min:0',
            'remove_avatar'   => 'nullable|boolean',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->boolean('remove_avatar') && $review->avatar) {
            Storage::disk('public')->delete($review->avatar);
            $data['avatar'] = null;
        }

        if ($request->hasFile('avatar')) {
            if ($review->avatar) {
                Storage::disk('public')->delete($review->avatar);
            }
            $media = $this->imageService->upload($request->file('avatar'), 'reviews', $request->name);
            $data['avatar'] = $media->path;
        }

        $review->update($data);

        return redirect()->route('admin.reviews.index')->with('success', 'Customer review updated successfully.');
    }

    public function toggleStatus(Review $review)
    {
        $review->status = $review->status === 'approved' ? 'pending' : 'approved';
        $review->save();

        return back()->with('success', "Review status updated to {$review->status}.");
    }

    public function toggleFeatured(Review $review)
    {
        $review->is_featured = !$review->is_featured;
        $review->save();

        $state = $review->is_featured ? 'marked as featured' : 'unmarked from featured';
        return back()->with('success', "Review {$state}.");
    }

    public function destroy(Review $review)
    {
        if ($review->avatar) {
            Storage::disk('public')->delete($review->avatar);
        }
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Customer review deleted successfully.');
    }
}
