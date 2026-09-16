@extends('layouts.admin')
@section('title', 'Quotations (RFQ)')

@section('content')
<div class="admin-topbar">
    <h1 class="admin-page-title">Quotations (RFQ)</h1>
</div>

<div class="card">
    <div class="table-wrapper quotation-desktop-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Quotation #</th>
                    <th>Customer</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quotations as $quotation)
                <tr>
                    <td class="text-teal font-bold text-sm">{{ $quotation->quotation_number }}</td>
                    <td>
                        <div style="font-weight:500;font-size:0.875rem;color:var(--text-primary)">{{ $quotation->user?->name }}</div>
                        <div class="text-xs text-muted">{{ $quotation->user?->company_name ?? $quotation->user?->email }}</div>
                    </td>
                    <td class="text-sm text-muted">{{ $quotation->items->count() }} {{ Str::plural('product', $quotation->items->count()) }}</td>
                    <td>
                        @if($quotation->status === 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($quotation->status === 'quoted')
                            <span class="badge badge-info">Quoted</span>
                        @elseif($quotation->status === 'accepted')
                            <span class="badge badge-success">Accepted</span>
                        @elseif($quotation->status === 'rejected')
                            <span class="badge badge-danger">Rejected</span>
                        @elseif($quotation->status === 'converted')
                            <span class="badge badge-primary">Converted</span>
                        @endif
                    </td>
                    <td class="text-sm text-muted">{{ $quotation->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.quotations.show', $quotation) }}" class="btn btn-secondary btn-sm">
                            @if($quotation->status === 'pending') ✏ Respond @else View @endif
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted" style="padding:var(--space-10)">No quotation requests yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards (Visible on <= 768px) -->
    <div class="quotation-mobile-cards" style="display:none;flex-direction:column;gap:12px;padding:12px">
        @forelse($quotations as $quotation)
        <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:10px;padding:14px;box-shadow:0 1px 2px rgba(0,0,0,0.03)">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
                <span style="font-weight:700;color:#0d7377;font-size:0.9rem">{{ $quotation->quotation_number }}</span>
                @if($quotation->status === 'pending')
                    <span class="badge badge-warning">Pending</span>
                @elseif($quotation->status === 'quoted')
                    <span class="badge badge-info">Quoted</span>
                @elseif($quotation->status === 'accepted')
                    <span class="badge badge-success">Accepted</span>
                @elseif($quotation->status === 'rejected')
                    <span class="badge badge-danger">Rejected</span>
                @elseif($quotation->status === 'converted')
                    <span class="badge badge-primary">Converted</span>
                @endif
            </div>
            <div style="font-weight:600;font-size:0.9rem;color:#0f172a;margin-bottom:2px">{{ $quotation->user?->name ?? '—' }}</div>
            <div style="font-size:0.78rem;color:#64748b;margin-bottom:10px">{{ $quotation->user?->company_name ?? $quotation->user?->email }}</div>
            <div style="display:flex;align-items:center;justify-content:space-between;border-top:1px solid #f1f5f9;padding-top:10px">
                <div style="font-size:0.75rem;color:#64748b">
                    {{ $quotation->items->count() }} {{ Str::plural('item', $quotation->items->count()) }} • {{ $quotation->created_at->format('d M Y') }}
                </div>
                <a href="{{ route('admin.quotations.show', $quotation) }}" class="btn btn-secondary btn-sm" style="font-size:0.8rem;padding:5px 12px">
                    @if($quotation->status === 'pending') ✏ Respond @else View @endif
                </a>
            </div>
        </div>
        @empty
        <div class="text-center text-muted" style="padding:24px">No quotation requests yet.</div>
        @endforelse
    </div>

    <div style="margin-top:var(--space-4);padding:0 12px 12px">{{ $quotations->links() }}</div>
</div>

<style>
@media (max-width: 768px) {
    .quotation-desktop-table {
        display: none !important;
    }
    .quotation-mobile-cards {
        display: flex !important;
    }
}
</style>
@endsection
