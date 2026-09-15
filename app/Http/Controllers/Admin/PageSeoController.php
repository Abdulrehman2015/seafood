<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSeo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageSeoController extends Controller
{
    public function index(Request $request)
    {
        PageSeo::ensureDefaults();

        $query = PageSeo::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('page_name', 'like', "%{$search}%")
                  ->orWhere('page_slug', 'like', "%{$search}%")
                  ->orWhere('meta_title', 'like', "%{$search}%")
                  ->orWhere('meta_description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            if ($request->type === 'system') {
                $query->where('is_system', true);
            } elseif ($request->type === 'custom') {
                $query->where('is_system', false);
            }
        }

        $allSeos = PageSeo::all();
        $stats = [
            'total'      => $allSeos->count(),
            'system'     => $allSeos->where('is_system', true)->count(),
            'custom'     => $allSeos->where('is_system', false)->count(),
            'optimized'  => $allSeos->filter(fn($s) => !empty($s->meta_title) && !empty($s->meta_description))->count(),
        ];

        $pageSeos = $query->orderBy('page_name')->get();

        return view('admin.page-seo.index', compact('pageSeos', 'stats'));
    }

    public function create()
    {
        $pageSeo = new PageSeo();
        $existingSlugs = PageSeo::pluck('page_slug')->toArray();

        // Only show pages that don't have an SEO record yet
        $defaultPages = array_filter(PageSeo::defaultPages(), function ($slug) use ($existingSlugs) {
            return !in_array($slug, $existingSlugs);
        }, ARRAY_FILTER_USE_KEY);

        return view('admin.page-seo.form', [
            'pageSeo'       => $pageSeo,
            'isEdit'        => false,
            'defaultPages'  => $defaultPages,
            'existingSlugs' => $existingSlugs,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'page_name'        => 'required|string|max:150',
            'page_slug'        => 'required|string|max:150|unique:page_seos,page_slug',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords'    => 'nullable|string|max:1000',
            'og_image'         => 'nullable|string|max:500',
            'canonical_url'    => 'nullable|string|max:500',
            'schema_markup'    => 'nullable|string',
        ]);

        $pageSlug = Str::slug($request->page_slug);
        if (empty($pageSlug)) {
            $pageSlug = Str::slug($request->page_name);
        }

        $pageSeo = PageSeo::create([
            'page_name'        => trim($request->page_name),
            'page_slug'        => $pageSlug,
            'meta_title'       => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords'    => $request->meta_keywords,
            'og_image'         => $request->og_image,
            'canonical_url'    => $request->canonical_url,
            'schema_markup'    => $request->schema_markup,
            'is_system'        => false,
        ]);

        return redirect()->route('admin.page-seo.index')->with('success', "Page SEO for '{$pageSeo->page_name}' created successfully.");
    }

    public function edit(PageSeo $pageSeo)
    {
        $defaultPages = PageSeo::defaultPages();
        $existingSlugs = PageSeo::where('id', '!=', $pageSeo->id)->pluck('page_slug')->toArray();

        return view('admin.page-seo.form', [
            'pageSeo'       => $pageSeo,
            'isEdit'        => true,
            'defaultPages'  => $defaultPages,
            'existingSlugs' => $existingSlugs,
        ]);
    }

    public function update(Request $request, PageSeo $pageSeo)
    {
        $request->validate([
            'page_name'        => 'required|string|max:150',
            'page_slug'        => 'required|string|max:150|unique:page_seos,page_slug,' . $pageSeo->id,
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords'    => 'nullable|string|max:1000',
            'og_image'         => 'nullable|string|max:500',
            'canonical_url'    => 'nullable|string|max:500',
            'schema_markup'    => 'nullable|string',
        ]);

        $pageSeo->update([
            'page_name'        => trim($request->page_name),
            'page_slug'        => Str::slug($request->page_slug),
            'meta_title'       => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords'    => $request->meta_keywords,
            'og_image'         => $request->og_image,
            'canonical_url'    => $request->canonical_url,
            'schema_markup'    => $request->schema_markup,
        ]);

        return redirect()->route('admin.page-seo.index')->with('success', "Page SEO for '{$pageSeo->page_name}' updated successfully.");
    }

    public function destroy(PageSeo $pageSeo)
    {
        $name = $pageSeo->page_name;
        $pageSeo->delete();

        return redirect()->route('admin.page-seo.index')->with('success', "Page SEO for '{$name}' removed.");
    }
}
