<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use App\Services\TranslationService;
use Database\Seeders\TranslationSeeder;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function __construct(
        protected TranslationService $translationService
    ) {}

    /**
     * Display Translation Manager.
     */
    public function index(Request $request)
    {
        $group  = $request->query('group', 'all');
        $status = $request->query('status', 'all');
        $search = trim($request->query('search', ''));

        $query = Translation::query();

        if ($group !== 'all' && !empty($group)) {
            $query->where('group', $group);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                  ->orWhere('text_en', 'like', "%{$search}%")
                  ->orWhere('text_zh', 'like', "%{$search}%")
                  ->orWhere('text_bm', 'like', "%{$search}%");
            });
        }

        if ($status === 'missing_zh') {
            $query->where(function ($q) {
                $q->whereNull('text_zh')->orWhere('text_zh', '');
            });
        } elseif ($status === 'missing_bm') {
            $query->where(function ($q) {
                $q->whereNull('text_bm')->orWhere('text_bm', '');
            });
        } elseif ($status === 'completed') {
            $query->whereNotNull('text_zh')->where('text_zh', '!=', '')
                  ->whereNotNull('text_bm')->where('text_bm', '!=', '');
        }

        $translations = $query->orderBy('group')->orderBy('key')->paginate(50)->withQueryString();

        // Statistics
        $totalCount    = Translation::count();
        $zhFilledCount = Translation::whereNotNull('text_zh')->where('text_zh', '!=', '')->count();
        $bmFilledCount = Translation::whereNotNull('text_bm')->where('text_bm', '!=', '')->count();
        $completedCount = Translation::whereNotNull('text_zh')->where('text_zh', '!=', '')
                                     ->whereNotNull('text_bm')->where('text_bm', '!=', '')->count();

        // Groups list with counts
        $groups = Translation::select('group', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('group')
            ->orderBy('group')
            ->get();

        return view('admin.translations.index', compact(
            'translations',
            'groups',
            'group',
            'status',
            'search',
            'totalCount',
            'zhFilledCount',
            'bmFilledCount',
            'completedCount'
        ));
    }

    /**
     * Batch update translations.
     */
    public function update(Request $request)
    {
        $items = $request->input('translations', []);

        if (empty($items) || !is_array($items)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'No translations provided.'], 422);
            }
            return back()->with('error', 'No translation changes detected.');
        }

        $count = $this->translationService->batchSave($items);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Successfully saved {$count} translation string(s).",
                'count'   => $count,
            ]);
        }

        return back()->with('success', "Successfully saved {$count} translation string(s).");
    }

    /**
     * Store new translation key.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group'   => 'required|string|max:60',
            'key'     => 'required|string|max:120',
            'text_en' => 'required|string',
            'text_zh' => 'nullable|string',
            'text_bm' => 'nullable|string',
        ]);

        $group = strtolower(trim($validated['group']));
        $key   = strtolower(trim(preg_replace('/[^a-zA-Z0-9_\.]/', '_', $validated['key'])));

        Translation::updateOrCreate(
            ['group' => $group, 'key' => $key],
            [
                'text_en' => $validated['text_en'],
                'text_zh' => $validated['text_zh'] ?? '',
                'text_bm' => $validated['text_bm'] ?? '',
            ]
        );

        $this->translationService->clearCache();
        $this->translationService->syncLangFiles();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => "Translation key '{$group}.{$key}' created."]);
        }

        return back()->with('success', "Translation key '{$group}.{$key}' successfully added.");
    }

    /**
     * Delete custom translation key.
     */
    public function destroy(Translation $translation, Request $request)
    {
        $label = "{$translation->group}.{$translation->key}";
        $translation->delete();

        $this->translationService->clearCache();
        $this->translationService->syncLangFiles();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => "Translation '{$label}' deleted."]);
        }

        return back()->with('success', "Translation '{$label}' deleted successfully.");
    }

    /**
     * Resync core seed strings.
     */
    public function sync(Request $request)
    {
        try {
            $seeder = new TranslationSeeder();
            $seeder->run();
            $this->translationService->clearCache();
            $this->translationService->syncLangFiles();

            return back()->with('success', 'Core translation dictionary synced successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to sync translations: ' . $e->getMessage());
        }
    }

    /**
     * Clear translation cache.
     */
    public function clearCache()
    {
        $this->translationService->clearCache();
        $this->translationService->syncLangFiles();
        return back()->with('success', 'Translation cache cleared and reloaded.');
    }
}
