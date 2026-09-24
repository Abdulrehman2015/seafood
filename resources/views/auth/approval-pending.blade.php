@extends('layouts.app')
@section('title', 'Account Pending Approval — MST Import and Export Sdn Bhd')

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:var(--space-2)">
            <span style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd">🏠 Home</span>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">Account Verification</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
                    <span style="background:rgba(245,158,11,0.2);border:1px solid rgba(251,191,36,0.4);padding:3px 10px;border-radius:999px;font-size:0.72rem;font-weight:700;color:#fde68a;text-transform:uppercase;letter-spacing:0.05em">
                        ⏳ Application In Review
                    </span>
                    <span style="color:#bae6fd;font-size:0.8rem">Business &amp; Trading Verification</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.75rem,3.5vw,2.4rem);margin-bottom:6px;letter-spacing:-0.02em">
                    @if(session('new_registration')) Application Submitted! @else Account Pending Verification @endif
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.95rem;max-width:680px;line-height:1.5;margin:0">
                    Your account application is currently under review by our commercial team.
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <div style="font-size:0.85rem;padding:6px 14px;border-radius:999px;box-shadow:0 2px 8px rgba(0,0,0,0.25);background:#091a36;color:#7dd3fc;border:1px solid #2563eb">
                    🔒 MST Security Protocol
                </div>
            </div>
        </div>
    </div>
</div>

<div style="min-height:60vh;padding:var(--space-10) var(--space-4) var(--space-16);display:flex;justify-content:center;background:#f8fafc">
    <div style="max-width:600px;width:100%">
        
        <!-- Main Pending Card -->
        <div class="card" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:18px;padding:36px 32px;box-shadow:0 10px 25px -5px rgba(0,0,0,0.06), 0 8px 10px -6px rgba(0,0,0,0.04);text-align:center">
            
            <div style="display:inline-flex;align-items:center;justify-content:center;width:72px;height:72px;border-radius:50%;background:#fef3c7;border:2px solid #fde68a;margin-bottom:20px;font-size:2.2rem">
                ⏳
            </div>

            @if(auth()->user()->customer_group === 'trading')
                <h2 style="font-family:var(--font-heading);font-size:1.5rem;font-weight:800;color:#0f274a;margin:0 0 12px">
                    @t('auth.trading_pending_title', 'Trading Account Request Received')
                </h2>

                <p style="color:#475569;font-size:0.95rem;line-height:1.65;margin:0 0 18px;text-align:left">
                    @t('auth.trading_pending_p1', 'Thank you for your interest in MST Import & Export Sdn. Bhd.')
                </p>
                <p style="color:#475569;font-size:0.95rem;line-height:1.65;margin:0 0 18px;text-align:left">
                    @t('auth.trading_pending_p2', 'Your Trading / Import & Distribution request has been received and will be reviewed by our team.')
                </p>
                <p style="color:#475569;font-size:0.95rem;line-height:1.65;margin:0 0 24px;text-align:left">
                    @t('auth.trading_pending_p3', 'We may contact you for additional business, product, destination or logistics information before providing a quotation or commercial proposal.')
                </p>

                @if(auth()->user()->company_name)
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:12px 18px;margin-bottom:24px;text-align:left;display:flex;align-items:center;gap:12px">
                        <span style="font-size:1.4rem">🏢</span>
                        <div>
                            <div style="font-size:0.75rem;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;font-weight:700">Registered Business Entity</div>
                            <div style="font-weight:700;color:#0f172a;font-size:0.95rem">{{ auth()->user()->company_name }}</div>
                        </div>
                    </div>
                @endif

                <!-- Trading Actions -->
                <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-bottom:18px">
                    <a href="{{ route('home') }}" class="btn btn-primary"
                       style="background:linear-gradient(135deg, #1d4ed8, #0f274a);border:none;font-weight:700;padding:11px 24px;border-radius:10px;text-decoration:none;color:#ffffff;display:inline-flex;align-items:center;gap:8px">
                        <span>@t('common.back_to_home', 'Back to Home')</span>
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-secondary"
                       style="background:#ffffff;border:1.5px solid #cbd5e1;color:#334155;font-weight:700;padding:11px 22px;border-radius:10px;text-decoration:none;display:inline-flex;align-items:center;gap:8px">
                        <span>@t('nav.contact', 'Contact MST')</span>
                    </a>
                </div>
            @else
                <h2 style="font-family:var(--font-heading);font-size:1.5rem;font-weight:800;color:#0f274a;margin:0 0 8px">
                    Verification in Progress
                </h2>

                <p style="color:#475569;font-size:0.95rem;line-height:1.65;margin:0 0 24px">
                    Thank you, <strong>{{ auth()->user()->name }}</strong>. Your application for a 
                    <span style="display:inline-block;padding:2px 8px;background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;border-radius:6px;font-weight:700;font-size:0.85rem;text-transform:uppercase">
                        {{ ucfirst(auth()->user()->customer_group) }} Account
                    </span> 
                    with <strong>MST Import & Export Sdn. Bhd.</strong> has been received.
                </p>

                @if(auth()->user()->company_name)
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:12px 18px;margin-bottom:24px;text-align:left;display:flex;align-items:center;gap:12px">
                        <span style="font-size:1.4rem">🏢</span>
                        <div>
                            <div style="font-size:0.75rem;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;font-weight:700">Registered Business Entity</div>
                            <div style="font-weight:700;color:#0f172a;font-size:0.95rem">{{ auth()->user()->company_name }}</div>
                        </div>
                    </div>
                @endif

                <!-- Next Steps -->
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:14px;padding:20px;margin-bottom:28px;text-align:left">
                    <h3 style="font-size:0.9rem;color:#166534;font-weight:700;margin:0 0 14px;text-transform:uppercase;letter-spacing:0.04em">
                        What happens next?
                    </h3>
                    <div style="display:flex;flex-direction:column;gap:12px">
                        <div style="display:flex;gap:12px;align-items:flex-start">
                            <span style="background:#22c55e;color:#ffffff;width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0">1</span>
                            <div style="font-size:0.88rem;color:#15803d;line-height:1.45">Our team reviews your business information within <strong>1–2 business days</strong>.</div>
                        </div>
                        <div style="display:flex;gap:12px;align-items:flex-start">
                            <span style="background:#22c55e;color:#ffffff;width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0">2</span>
                            <div style="font-size:0.88rem;color:#15803d;line-height:1.45">Once approved, you will be able to access applicable business features and manage orders.</div>
                        </div>
                        <div style="display:flex;gap:12px;align-items:flex-start">
                            <span style="background:#22c55e;color:#ffffff;width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0">3</span>
                            <div style="font-size:0.88rem;color:#15803d;line-height:1.45">You will also receive an email notification confirming your business account status.</div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-bottom:18px">
                    <button type="button" onclick="handleManualRefresh()" class="btn btn-primary" id="btnRefreshStatus"
                            style="background:linear-gradient(135deg, #1d4ed8, #0f274a);border:none;font-weight:700;padding:11px 22px;border-radius:10px;display:inline-flex;align-items:center;gap:8px">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/>
                        </svg>
                        <span>Refresh Status</span>
                    </button>

                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="background:#ffffff;border:1.5px solid #cbd5e1;color:#475569;font-weight:600;padding:11px 20px;border-radius:10px">
                            Sign Out
                        </button>
                    </form>
                </div>
            @endif

            <div style="font-size:0.8rem;color:#64748b">
                Need urgent assistance? <a href="https://wa.me/601112710260?text=Hi%20MST%20Import%20%26%20Export,%20I%20have%20submitted%20a%20wholesale%20account%20application." target="_blank" style="color:#2563eb;font-weight:700;text-decoration:underline">WhatsApp Our Desk</a>
            </div>

            <!-- Live Status Poller Indicator -->
            <div style="margin-top:20px;display:inline-flex;align-items:center;gap:8px;padding:6px 14px;border-radius:999px;background:#f1f5f9;font-size:0.75rem;color:#64748b">
                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#3b82f6;animation:pulseDot 1.5s infinite"></span>
                <span>Auto-checking status in background...</span>
            </div>

        </div>

    </div>
</div>

<style>
@keyframes pulseDot {
    0% { transform: scale(0.9); opacity: 0.6; }
    50% { transform: scale(1.3); opacity: 1; }
    100% { transform: scale(0.9); opacity: 0.6; }
}
</style>

<script>
function handleManualRefresh() {
    var btn = document.getElementById('btnRefreshStatus');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = 'Checking status...';
    }
    window.location.reload();
}

// Live polling: Check status every 4 seconds. Redirect immediately when admin changes status!
(function() {
    var pollInterval = setInterval(function() {
        fetch('{{ route("approval.check_status") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (!data) return;
            if (data.approved) {
                clearInterval(pollInterval);
                window.location.href = data.redirect || '{{ route("account.dashboard") }}';
            } else if (data.rejected) {
                clearInterval(pollInterval);
                window.location.href = '{{ route("approval.rejected") }}';
            }
        })
        .catch(function() {
            // Silently ignore network hiccups
        });
    }, 4000);
})();
</script>
@endsection
