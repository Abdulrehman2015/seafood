@extends('layouts.app')
@section('title', 'Account Application Status — Mika Import and Export SDN Bhd')

@section('content')
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:var(--space-8)">
    <div style="max-width:540px;width:100%;text-align:center">
        <div style="font-size:4rem;margin-bottom:var(--space-5)">⚠️</div>
        <h1 style="font-family:var(--font-heading);margin-bottom:var(--space-4)">
            Application Not Approved
        </h1>
        <p class="text-muted" style="margin-bottom:var(--space-8);line-height:1.8">
            Unfortunately, your wholesale or trading account application could not be verified with the business details provided.
        </p>

        @auth
            @if(auth()->user()->rejection_reason)
            <div class="card p-6 mb-6" style="text-align:left;border-left:4px solid var(--coral)">
                <h3 style="font-size:0.95rem;color:var(--coral);margin-bottom:var(--space-2)">Reason from Administration:</h3>
                <p class="text-sm text-secondary">{{ auth()->user()->rejection_reason }}</p>
            </div>
            @endif
        @endauth

        <div class="glass-card p-6" style="margin-bottom:var(--space-6);text-align:left">
            <h3 style="font-size:1rem;margin-bottom:var(--space-2)">Need Assistance or Re-application?</h3>
            <p class="text-sm text-muted mb-4">
                If you believe this was an error or would like to submit additional business registration documents (SSM, trade licenses), please contact our sales desk directly.
            </p>
            <div style="font-size:0.875rem;color:var(--text-secondary)">
                📞 WhatsApp/Phone: +60 12-345 6789<br>
                ✉️ Email: mikatrading15@gmail.com
            </div>
        </div>

        <div style="display:flex;gap:var(--space-4);justify-content:center;flex-wrap:wrap">
            <a href="{{ route('home') }}" class="btn btn-secondary">Back to Retail Store</a>
            <a href="mailto:mikatrading15@gmail.com" class="btn btn-primary">Contact Support</a>
        </div>
    </div>
</div>
@endsection
