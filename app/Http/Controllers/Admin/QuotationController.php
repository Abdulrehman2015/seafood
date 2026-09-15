<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $quotations = Quotation::with('user')
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.quotations.index', compact('quotations'));
    }

    public function show(Quotation $quotation)
    {
        $quotation->load('user', 'items.product');
        return view('admin.quotations.show', compact('quotation'));
    }

    public function respond(Request $request, Quotation $quotation)
    {
        $request->validate([
            'items'                    => 'required|array',
            'items.*.quotation_item_id'=> 'required|exists:quotation_items,id',
            'items.*.quoted_price'     => 'required|numeric|min:0',
            'admin_notes'              => 'nullable|string|max:1000',
            'valid_until'              => 'required|date|after:today',
        ]);

        $total = 0;

        foreach ($request->items as $item) {
            $qi = $quotation->items()->findOrFail($item['quotation_item_id']);
            $subtotal = $item['quoted_price'] * $qi->quantity_requested;
            $qi->update([
                'quoted_price' => $item['quoted_price'],
                'subtotal'     => $subtotal,
            ]);
            $total += $subtotal;
        }

        $quotation->update([
            'status'        => 'quoted',
            'admin_notes'   => $request->admin_notes,
            'valid_until'   => $request->valid_until,
            'total_quoted'  => $total,
        ]);

        // Notify customer
        try {
            \Mail::to($quotation->user->email)->send(new \App\Mail\QuotationReady($quotation));
        } catch (\Exception $e) {}

        return redirect()->route('admin.quotations.index')
            ->with('success', 'Quotation response sent to ' . $quotation->user->name);
    }
}
