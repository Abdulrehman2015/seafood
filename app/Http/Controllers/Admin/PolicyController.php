<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PolicyController extends Controller
{
    /**
     * Display a listing of the policies/pages.
     */
    public function index(Request $request)
    {
        $query = Policy::query();

        // Search by title or summary
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('title_zh', 'like', "%{$search}%")
                  ->orWhere('title_bm', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->input('status')) {
            if (in_array($status, ['published', 'draft'])) {
                $query->where('status', $status);
            }
        }

        $policies = $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->paginate(15)->withQueryString();

        $stats = [
            'total' => Policy::count(),
            'published' => Policy::where('status', 'published')->count(),
            'draft' => Policy::where('status', 'draft')->count(),
        ];

        return view('admin.policies.index', compact('policies', 'stats'));
    }

    /**
     * Show the form for creating a new policy/page.
     */
    public function create()
    {
        $maxSort = Policy::max('sort_order') ?? 0;
        return view('admin.policies.create', [
            'nextSortOrder' => $maxSort + 1,
        ]);
    }

    /**
     * Store a newly created policy/page in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:policies,slug',
            'content' => 'required|string',
            'summary' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft',
            'sort_order' => 'nullable|integer|min:0',
            'title_zh' => 'nullable|string|max:255',
            'content_zh' => 'nullable|string',
            'title_bm' => 'nullable|string|max:255',
            'content_bm' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Policy::generateSlug($validated['title']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $validated['sort_order'] = $validated['sort_order'] ?? (Policy::max('sort_order') + 1);

        $policy = Policy::create($validated);

        return redirect()->route('admin.policies.index')->with('success', "Page '{$policy->title}' created successfully.");
    }

    /**
     * Show the form for editing the specified policy/page.
     */
    public function edit(Policy $policy)
    {
        return view('admin.policies.edit', compact('policy'));
    }

    /**
     * Update the specified policy/page in storage.
     */
    public function update(Request $request, Policy $policy)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:policies,slug,' . $policy->id,
            'content' => 'required|string',
            'summary' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft',
            'sort_order' => 'nullable|integer|min:0',
            'title_zh' => 'nullable|string|max:255',
            'content_zh' => 'nullable|string',
            'title_bm' => 'nullable|string|max:255',
            'content_bm' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['sort_order'] = $validated['sort_order'] ?? $policy->sort_order;

        $policy->update($validated);

        return redirect()->route('admin.policies.index')->with('success', "Page '{$policy->title}' updated successfully.");
    }

    /**
     * Toggle policy publish status (published <-> draft).
     */
    public function toggleStatus(Policy $policy)
    {
        $newStatus = $policy->status === 'published' ? 'draft' : 'published';
        $policy->update(['status' => $newStatus]);

        return back()->with('success', "Page '{$policy->title}' status changed to " . ucfirst($newStatus) . '.');
    }

    /**
     * Remove the specified policy/page from storage.
     */
    public function destroy(Policy $policy)
    {
        $title = $policy->title;
        $policy->delete();

        return redirect()->route('admin.policies.index')->with('success', "Page '{$title}' deleted successfully.");
    }
}
