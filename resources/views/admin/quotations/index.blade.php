@extends('layouts.admin')
@section('title', 'Quotations (RFQ)')

@section('content')
<div class="admin-topbar">
    <h1 class="admin-page-title">Quotations (RFQ)</h1>
</div>

<div class="card">
    <div class="table-wrapper">
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
    <div style="margin-top:var(--space-4)">{{ $quotations->links() }}</div>
</div>
@endsection
