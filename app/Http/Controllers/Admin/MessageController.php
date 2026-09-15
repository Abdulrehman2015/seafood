<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->get('filter') === 'unread') {
            $query->where('is_read', false);
        } elseif ($request->get('filter') === 'read') {
            $query->where('is_read', true);
        }

        $messages = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total'  => ContactMessage::count(),
            'unread' => ContactMessage::where('is_read', false)->count(),
            'read'   => ContactMessage::where('is_read', true)->count(),
        ];

        return view('admin.messages.index', compact('messages', 'stats'));
    }

    public function show(ContactMessage $message)
    {
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'Message deleted successfully.');
    }
}
