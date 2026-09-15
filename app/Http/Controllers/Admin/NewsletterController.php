<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterController extends Controller
{
    /**
     * Display a listing of newsletter subscribers.
     */
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query()->latest();

        if ($search = $request->input('search')) {
            $query->where('email', 'like', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            if (in_array($status, ['active', 'unsubscribed'])) {
                $query->where('status', $status);
            }
        }

        $subscribers = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::where('status', 'active')->count(),
            'unsubscribed' => NewsletterSubscriber::where('status', 'unsubscribed')->count(),
            'this_month' => NewsletterSubscriber::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view('admin.newsletter.index', compact('subscribers', 'stats'));
    }

    /**
     * Toggle subscriber status between active and unsubscribed.
     */
    public function toggleStatus(NewsletterSubscriber $subscriber)
    {
        $newStatus = $subscriber->status === 'active' ? 'unsubscribed' : 'active';
        $subscriber->update(['status' => $newStatus]);

        return back()->with('success', "Subscriber {$subscriber->email} status updated to " . ucfirst($newStatus) . '.');
    }

    /**
     * Remove the specified subscriber.
     */
    public function destroy(NewsletterSubscriber $subscriber)
    {
        $email = $subscriber->email;
        $subscriber->delete();

        return back()->with('success', "Subscriber {$email} has been removed.");
    }

    /**
     * Export subscribers list as CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $fileName = 'newsletter_subscribers_' . date('Y-m-d_His') . '.csv';

        $query = NewsletterSubscriber::query()->latest();
        if ($status = $request->input('status')) {
            if (in_array($status, ['active', 'unsubscribed'])) {
                $query->where('status', $status);
            }
        }

        $subscribers = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($subscribers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Email Address', 'Status', 'IP Address', 'Date Subscribed']);

            foreach ($subscribers as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->email,
                    ucfirst($row->status),
                    $row->ip_address ?? 'N/A',
                    $row->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
