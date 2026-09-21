<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    /**
     * Display the specified policy/page.
     */
    public function show(Request $request, ?string $locale = null, ?string $slug = null)
    {
        // Handle routes where slug is passed as first parameter (e.g. /policy/{slug})
        if ($slug === null && $locale !== null) {
            $slug = $locale;
            $locale = app()->getLocale();
        }

        if ($locale && in_array($locale, ['en', 'zh', 'bm'])) {
            app()->setLocale($locale);
        }

        // Admins can preview drafts; visitors only see published policies
        $query = Policy::query();
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            $query->where('status', 'published');
        }

        $policy = $query->where('slug', $slug)->firstOrFail();

        // Get all published policies for sidebar / quick switcher
        $allPolicies = Policy::published()->get();

        return view('policy.show', compact('policy', 'allPolicies'));
    }
}
