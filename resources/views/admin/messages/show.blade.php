@extends('layouts.admin')
@section('title', 'Inquiry: ' . ($message->subject ?? 'Message') . ' — Admin')

@section('content')

<!-- Topbar Header -->
<div class="admin-topbar" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid #e2e8f0;">
    <div>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:4px;">
            <h1 class="admin-page-title" style="margin:0;font-size:clamp(1.3rem,2.5vw,1.65rem);font-weight:700;color:#0f172a;">
                {{ $message->subject ?? 'Customer Inquiry' }}
            </h1>
            <span style="background:#f1f5f9;color:#475569;font-size:0.75rem;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid #e2e8f0;">
                ✓ Read
            </span>
        </div>
        <p class="text-sm text-muted" style="margin:0;color:#64748b;">
            Received on {{ $message->created_at->format('l, d F Y at h:i A') }} ({{ $message->created_at->diffForHumans() }})
        </p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        <a href="mailto:{{ $message->email }}?subject={{ urlencode('Re: ' . ($message->subject ?? 'Your Inquiry to Mika Import and Export SDN Bhd')) }}" class="btn btn-primary btn-sm" style="font-weight:600;padding:8px 16px;border-radius:8px;display:inline-flex;align-items:center;gap:6px;">
            ✉ Reply via Email
        </a>
        @if($message->phone)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $message->phone) }}" target="_blank" class="btn btn-secondary btn-sm" style="color:#16a34a;font-weight:600;padding:8px 14px;border-radius:8px;display:inline-flex;align-items:center;gap:6px;" title="Chat on WhatsApp">
                💬 WhatsApp
            </a>
        @endif
        <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary btn-sm" style="font-weight:600;padding:8px 14px;border-radius:8px;display:inline-flex;align-items:center;gap:6px;">
            ← Back to Inquiries
        </a>
    </div>
</div>

<div class="admin-form-layout" style="margin-bottom:24px;">
    
    <!-- Left: Message Body Card -->
    <div class="card" style="padding:24px;border-radius:14px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
        <div style="border-bottom:1px solid #f1f5f9;padding-bottom:14px;margin-bottom:18px;">
            <div style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.04em;color:#0f766e;font-weight:700;margin-bottom:4px;">
                Subject
            </div>
            <h2 style="font-size:1.25rem;color:#0f172a;margin:0;font-weight:700;">
                {{ $message->subject ?? 'General Enquiry' }}
            </h2>
        </div>

        <div style="font-size:0.95rem;line-height:1.8;color:#1e293b;white-space:pre-wrap;background:#f8fafc;padding:20px;border-radius:10px;border:1px solid #e2e8f0;font-family:inherit;">
            {{ $message->message }}
        </div>

        <div style="margin-top:24px;display:flex;justify-content:space-between;align-items:center;padding-top:16px;border-top:1px solid #f1f5f9;flex-wrap:wrap;gap:12px;">
            <div class="text-xs text-muted" style="font-size:0.78rem;color:#94a3b8;">
                Sender IP Address: {{ $message->ip_address ?? 'N/A' }}
            </div>
            <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Delete this message permanently?')" style="margin:0;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" style="font-weight:600;padding:7px 14px;border-radius:8px;">
                    🗑 Delete Message
                </button>
            </form>
        </div>
    </div>

    <!-- Right: Sender Details Sidebar -->
    <div class="card" style="padding:22px;border-radius:14px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
        <div style="padding-bottom:12px;border-bottom:1px solid #f1f5f9;margin-bottom:16px;">
            <h3 style="font-size:1rem;font-weight:700;color:#0f172a;margin:0;">
                👤 Sender Details
            </h3>
        </div>

        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;padding-bottom:14px;border-bottom:1px solid #f8fafc;">
            <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#0d9488,#0f766e);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.1rem;flex-shrink:0;">
                {{ strtoupper(substr($message->name, 0, 1)) }}
            </div>
            <div style="min-width:0;">
                <div style="font-weight:700;color:#0f172a;font-size:0.95rem;">
                    {{ $message->name }}
                </div>
                <div style="font-size:0.78rem;color:#64748b;">
                    Website Visitor
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:14px;font-size:0.875rem;">
            <div>
                <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Email Address</div>
                <div style="margin-top:2px;">
                    <a href="mailto:{{ $message->email }}" style="color:#0f766e;text-decoration:none;font-weight:600;word-break:break-all;">
                        {{ $message->email }}
                    </a>
                </div>
            </div>

            <div>
                <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Phone Number</div>
                <div style="margin-top:2px;">
                    @if($message->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $message->phone) }}" target="_blank" style="color:#16a34a;text-decoration:none;font-weight:600;">
                            💬 {{ $message->phone }}
                        </a>
                    @else
                        <span style="color:#94a3b8;">Not provided</span>
                    @endif
                </div>
            </div>

            <div>
                <div class="text-xs text-muted" style="color:#64748b;font-weight:600;">Received At</div>
                <div style="color:#334155;margin-top:2px;">
                    {{ $message->created_at->format('d M Y, h:i A') }}
                </div>
            </div>

            <div style="margin-top:10px;border-top:1px solid #f1f5f9;padding-top:14px;">
                <a href="mailto:{{ $message->email }}?subject={{ urlencode('Re: ' . ($message->subject ?? 'Your Inquiry to Mika Import and Export SDN Bhd')) }}" class="btn btn-primary btn-sm" style="width:100%;text-align:center;justify-content:center;font-weight:600;padding:9px 14px;border-radius:8px;">
                    ✉ Compose Email Reply
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
