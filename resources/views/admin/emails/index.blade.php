@extends('layouts.admin')
@section('title', 'Email Templates & Notification Gateway — Admin')

@section('content')

{{-- Topbar Header --}}
<div class="email-topbar-card">
    <div class="email-topbar-left">
        <div class="email-title-row">
            <div class="email-header-icon-box">
                ✉️
            </div>
            <div>
                <h1 class="email-header-title">
                    Email Templates &amp; Notifications
                </h1>
                <p class="email-header-sub">
                    Manage, preview, and test-send responsive HTML email notifications for customer authentication, admin alerts, approvals, and order tracking.
                </p>
            </div>
        </div>
    </div>
    <div class="email-topbar-actions">
        <span class="email-total-pill">
            <span class="pulse-dot"></span>
            {{ count($templates) }} Active Templates
        </span>
        <a href="{{ route('admin.settings.index', ['tab' => 'smtp']) }}" class="btn-smtp-config">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.38a2 2 0 0 0-.73-2.73l-.15-.09a2 2 0 0 1-1-1.74v-.51a2 2 0 0 1 1-1.72l.15-.1a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            <span>SMTP Gateway Settings</span>
        </a>
    </div>
</div>

{{-- Quick Stat Metrics --}}
<div class="email-metrics-grid">
    <div class="email-metric-card">
        <div class="email-metric-info">
            <span class="email-metric-label">Total Templates</span>
            <div class="email-metric-value">{{ count($templates) }}</div>
        </div>
        <div class="email-metric-icon icon-blue">
            📨
        </div>
    </div>

    <div class="email-metric-card">
        <div class="email-metric-info">
            <span class="email-metric-label">Customer Auth</span>
            <div class="email-metric-value text-cyan">3</div>
        </div>
        <div class="email-metric-icon icon-cyan">
            👤
        </div>
    </div>

    <div class="email-metric-card">
        <div class="email-metric-info">
            <span class="email-metric-label">Admin Alerts</span>
            <div class="email-metric-value text-purple">2</div>
        </div>
        <div class="email-metric-icon icon-purple">
            🔔
        </div>
    </div>

    <div class="email-metric-card">
        <div class="email-metric-info">
            <span class="email-metric-label">Commercial &amp; Orders</span>
            <div class="email-metric-value text-emerald">4</div>
        </div>
        <div class="email-metric-icon icon-emerald">
            📦
        </div>
    </div>
</div>

{{-- Filter & Search Bar --}}
<div class="email-filter-bar">
    <div class="email-search-box">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" style="flex-shrink:0"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input type="text" id="emailSearchInput" placeholder="Search email templates..." onkeyup="filterEmailTemplates()">
    </div>
    <div class="email-category-tabs">
        <button class="filter-tab active" onclick="filterCategory('all', this)">All ({{ count($templates) }})</button>
        <button class="filter-tab" onclick="filterCategory('Authentication', this)">Authentication</button>
        <button class="filter-tab" onclick="filterCategory('Admin Notifications', this)">Admin Alerts</button>
        <button class="filter-tab" onclick="filterCategory('Customer Status', this)">Status Updates</button>
        <button class="filter-tab" onclick="filterCategory('Commercial & Trading', this)">Orders &amp; RFQ</button>
    </div>
</div>

{{-- Templates Grid --}}
<div class="email-templates-grid" id="templatesGrid">
    @foreach($templates as $key => $tpl)
    <div class="email-tpl-card" data-category="{{ $tpl['category'] }}" data-search="{{ strtolower($tpl['name'] . ' ' . $tpl['category'] . ' ' . $tpl['recipient'] . ' ' . $tpl['subject']) }}">
        <div class="email-card-top">
            <div class="email-tpl-header">
                <div class="email-tpl-icon">{{ $tpl['icon'] }}</div>
                <div class="email-tpl-title-group">
                    <span class="email-category-badge">{{ $tpl['category'] }}</span>
                    <h3 class="email-tpl-name">{{ $tpl['name'] }}</h3>
                </div>
            </div>

            <div class="email-tpl-body">
                <p class="email-tpl-desc">{{ $tpl['description'] }}</p>
                
                <div class="email-meta-box">
                    <div class="email-meta-item">
                        <span class="email-meta-label">Recipient</span>
                        <span class="email-recipient-pill">{{ $tpl['recipient'] }}</span>
                    </div>

                    <div class="email-meta-item">
                        <span class="email-meta-label">Default Subject</span>
                        <span class="email-subject-preview" title="{{ $tpl['subject'] }}">{{ $tpl['subject'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="email-tpl-actions">
            <button type="button" class="btn-email-preview" 
                    onclick="openEmailPreview('{{ $key }}', '{{ addslashes($tpl['name']) }}', '{{ route('admin.emails.preview', $key) }}', '{{ route('admin.emails.send-test', $key) }}')">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                <span>Live Preview</span>
            </button>
            <button type="button" class="btn-email-send"
                    onclick="openSendTestModal('{{ $key }}', '{{ addslashes($tpl['name']) }}', '{{ route('admin.emails.send-test', $key) }}')">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                <span>Test Send</span>
            </button>
        </div>
    </div>
    @endforeach
</div>

{{-- No Results State --}}
<div id="noResultsState" class="email-empty-state" style="display:none">
    <div class="empty-icon">🔍</div>
    <h3>No matching email templates found</h3>
    <p>Try adjusting your search query or switching category filters.</p>
</div>

{{-- Live Preview Modal --}}
<div id="emailPreviewModal" class="email-modal-backdrop" style="display:none" onclick="handleBackdropClick(event)">
    <div class="email-modal-container">
        <div class="email-modal-header">
            <div class="email-modal-title-group">
                <div class="modal-badge">HTML Simulation</div>
                <h3 id="previewModalTitle">Email Preview</h3>
            </div>
            
            <div class="email-modal-controls">
                {{-- Device Toggle --}}
                <div class="email-device-toggle">
                    <button type="button" id="btnDesktopView" class="email-device-btn active" onclick="setPreviewWidth('desktop')">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                        <span>Desktop</span>
                    </button>
                    <button type="button" id="btnMobileView" class="email-device-btn" onclick="setPreviewWidth('mobile')">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                        <span>Mobile</span>
                    </button>
                </div>

                <button type="button" class="email-modal-close" onclick="closeEmailPreview()" title="Close (Esc)">✕</button>
            </div>
        </div>

        <div class="email-modal-body">
            <div class="email-iframe-wrapper">
                <iframe id="emailPreviewIframe" src="about:blank" frameborder="0"></iframe>
            </div>
        </div>

        <div class="email-modal-footer">
            <form id="modalSendTestForm" method="POST" action="" class="modal-test-form">
                @csrf
                <div class="input-group-email">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" style="flex-shrink:0"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <input type="email" name="email" class="email-input-field"
                           placeholder="Recipient email address..." value="{{ auth()->user()->email ?? '' }}" required>
                </div>
                <div class="modal-footer-btns">
                    <button type="submit" class="btn-send-now">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        <span>Send Test Email</span>
                    </button>
                    <button type="button" class="btn-cancel-modal" onclick="closeEmailPreview()">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
/* ===== EMAIL TEMPLATES & NOTIFICATION GATEWAY SYSTEM STYLES ===== */

.email-topbar-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
}

.email-topbar-left {
    flex: 1;
    min-width: 280px;
}

.email-title-row {
    display: flex;
    align-items: center;
    gap: 14px;
}

.email-header-icon-box {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border: 1px solid #bfdbfe;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.email-header-title {
    margin: 0 0 4px 0;
    font-size: clamp(1.2rem, 2vw, 1.5rem);
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.02em;
}

.email-header-sub {
    margin: 0;
    font-size: 0.88rem;
    color: #64748b;
    line-height: 1.45;
}

.email-topbar-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.email-total-pill {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
    font-size: 0.82rem;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 9999px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}

.pulse-dot {
    width: 8px;
    height: 8px;
    background-color: #22c55e;
    border-radius: 50%;
    box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
    animation: pulseDot 2s infinite;
}

@keyframes pulseDot {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
}

.btn-smtp-config {
    background: #0f172a;
    color: #ffffff;
    font-weight: 600;
    font-size: 0.88rem;
    padding: 9px 18px;
    border-radius: 10px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.btn-smtp-config:hover {
    background: #1e293b;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
}

/* Metrics Grid */
.email-metrics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.email-metric-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    transition: all 0.2s ease;
}

.email-metric-card:hover {
    border-color: #cbd5e1;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
}

.email-metric-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
    display: block;
    margin-bottom: 4px;
}

.email-metric-value {
    font-size: 1.65rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
}

.text-cyan { color: #0284c7 !important; }
.text-purple { color: #7c3aed !important; }
.text-emerald { color: #059669 !important; }

.email-metric-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.icon-blue { background: #eff6ff; color: #2563eb; }
.icon-cyan { background: #e0f2fe; color: #0284c7; }
.icon-purple { background: #f5f3ff; color: #7c3aed; }
.icon-emerald { background: #ecfdf5; color: #059669; }

/* Filter Bar */
.email-filter-bar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px 16px;
    margin-bottom: 24px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

.email-search-box {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    padding: 9px 14px;
    width: 100%;
    box-sizing: border-box;
    transition: all 0.15s ease;
}

.email-search-box:focus-within {
    background: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.email-search-box input {
    border: none;
    background: transparent;
    outline: none;
    font-size: 0.88rem;
    color: #0f172a;
    width: 100%;
    min-width: 0;
}

.email-category-tabs {
    display: flex;
    gap: 8px;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    padding-bottom: 4px;
    scrollbar-width: none; /* Firefox */
}

.email-category-tabs::-webkit-scrollbar {
    display: none; /* Chrome/Safari */
}

.filter-tab {
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    color: #475569;
    font-size: 0.82rem;
    font-weight: 600;
    padding: 7px 15px;
    border-radius: 8px;
    cursor: pointer;
    flex-shrink: 0;
    white-space: nowrap;
    transition: all 0.15s ease;
}

.filter-tab:hover {
    background: #f1f5f9;
    color: #0f172a;
}

.filter-tab.active {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
}

/* Template Cards Grid */
.email-templates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
}

.email-tpl-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.2s ease;
}

.email-tpl-card:hover {
    border-color: #93c5fd;
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.08);
}

.email-card-top {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.email-tpl-header {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 14px;
}

.email-tpl-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
}

.email-tpl-title-group {
    flex: 1;
    min-width: 0;
}

.email-category-badge {
    display: inline-block;
    font-size: 0.68rem;
    font-weight: 800;
    color: #2563eb;
    background: #eff6ff;
    padding: 2px 8px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 4px;
}

.email-tpl-name {
    font-size: 0.98rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    line-height: 1.35;
}

.email-tpl-body {
    margin-bottom: 18px;
    flex: 1;
}

.email-tpl-desc {
    font-size: 0.84rem;
    color: #475569;
    line-height: 1.5;
    margin: 0 0 14px 0;
}

.email-meta-box {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 10px;
    padding: 10px 12px;
}

.email-meta-item {
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.email-meta-item + .email-meta-item {
    margin-top: 6px;
    padding-top: 6px;
    border-top: 1px dashed #e2e8f0;
}

.email-meta-label {
    color: #94a3b8;
    font-weight: 600;
    font-size: 0.74rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    flex-shrink: 0;
}

.email-recipient-pill {
    background: #ffffff;
    color: #334155;
    border: 1px solid #cbd5e1;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 0.74rem;
}

.email-subject-preview {
    color: #0f172a;
    font-weight: 600;
    font-size: 0.78rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 170px;
    text-align: right;
}

.email-tpl-actions {
    display: flex;
    gap: 10px;
    border-top: 1px solid #f1f5f9;
    padding-top: 14px;
}

.btn-email-preview {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 9px 12px;
    border-radius: 10px;
    font-size: 0.84rem;
    font-weight: 600;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #334155;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap;
}

.btn-email-preview:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    color: #0f172a;
}

.btn-email-send {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 9px 12px;
    border-radius: 10px;
    font-size: 0.84rem;
    font-weight: 700;
    background: #2563eb;
    border: 1px solid #2563eb;
    color: #ffffff;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap;
}

.btn-email-send:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
    box-shadow: 0 3px 10px rgba(37, 99, 235, 0.25);
}

.email-empty-state {
    text-align: center;
    padding: 48px 20px;
    background: #ffffff;
    border: 1px dashed #cbd5e1;
    border-radius: 16px;
}

.empty-icon {
    font-size: 2.5rem;
    margin-bottom: 12px;
}

/* Modal Styles */
.email-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(6px);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
}

.email-modal-container {
    background: #ffffff;
    border-radius: 20px;
    width: 100%;
    max-width: 980px;
    height: 92vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    animation: modalIn 0.22s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes modalIn {
    from { opacity: 0; transform: scale(0.96) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.email-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 24px;
    border-bottom: 1px solid #e2e8f0;
    background: #ffffff;
}

.modal-badge {
    font-size: 0.68rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #2563eb;
    background: #eff6ff;
    padding: 2px 8px;
    border-radius: 4px;
    display: inline-block;
    margin-bottom: 2px;
}

#previewModalTitle {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 800;
    color: #0f172a;
}

.email-modal-controls {
    display: flex;
    align-items: center;
    gap: 12px;
}

.email-device-toggle {
    display: flex;
    background: #f1f5f9;
    padding: 3px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}

.email-device-btn {
    border: none;
    background: transparent;
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
    padding: 6px 12px;
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
}

.email-device-btn.active {
    background: #ffffff;
    color: #0f172a;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}

.email-modal-close {
    background: #f1f5f9;
    border: none;
    font-size: 1.1rem;
    color: #64748b;
    cursor: pointer;
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
}

.email-modal-close:hover { background: #e2e8f0; color: #0f172a; }

.email-modal-body {
    flex: 1;
    background: #94a3b8;
    overflow-y: auto;
    padding: 24px 16px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
}

.email-iframe-wrapper {
    width: 100%;
    max-width: 680px;
    height: 100%;
    min-height: 520px;
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    overflow: hidden;
    transition: max-width 0.25s ease;
}

.email-iframe-wrapper.mobile-view {
    max-width: 375px;
    border: 8px solid #1e293b;
    border-radius: 28px;
}

.email-iframe-wrapper iframe {
    width: 100%;
    height: 100%;
    border: none;
    background: #ffffff;
}

.email-modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #e2e8f0;
    background: #f8fafc;
}

.modal-test-form {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.input-group-email {
    flex: 1;
    min-width: 240px;
    display: flex;
    align-items: center;
    gap: 10px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    padding: 8px 14px;
    transition: all 0.15s ease;
}

.input-group-email:focus-within {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.email-input-field {
    border: none;
    outline: none;
    background: transparent;
    width: 100%;
    font-size: 0.88rem;
    color: #0f172a;
}

.modal-footer-btns {
    display: flex;
    gap: 10px;
    align-items: center;
}

.btn-send-now {
    background: #2563eb;
    color: #ffffff;
    border: none;
    font-weight: 700;
    font-size: 0.88rem;
    padding: 9px 20px;
    border-radius: 10px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.15s ease;
    white-space: nowrap;
}

.btn-send-now:hover {
    background: #1d4ed8;
    box-shadow: 0 3px 10px rgba(37, 99, 235, 0.25);
}

.btn-cancel-modal {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #475569;
    font-weight: 600;
    font-size: 0.88rem;
    padding: 9px 18px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.15s ease;
}

.btn-cancel-modal:hover {
    background: #f1f5f9;
    color: #0f172a;
}

/* ===== RESPONSIVE MEDIA QUERIES ===== */

@media (max-width: 1024px) {
    .email-metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .email-topbar-card {
        padding: 16px;
    }
    
    .email-topbar-actions {
        width: 100%;
        justify-content: space-between;
    }

    .btn-smtp-config {
        flex: 1;
        justify-content: center;
    }
}

@media (max-width: 640px) {
    .email-metrics-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .email-metric-card {
        padding: 14px;
    }

    .email-metric-value {
        font-size: 1.4rem;
    }

    .email-metric-icon {
        width: 36px;
        height: 36px;
        font-size: 1.1rem;
    }

    .email-templates-grid {
        grid-template-columns: 1fr;
    }

    .email-modal-container {
        height: 100vh;
        border-radius: 0;
    }

    .email-modal-header {
        padding: 12px 16px;
    }

    .email-modal-footer {
        padding: 12px 16px;
    }

    .modal-test-form {
        flex-direction: column;
        align-items: stretch;
    }

    .modal-footer-btns {
        width: 100%;
    }

    .btn-send-now, .btn-cancel-modal {
        flex: 1;
        justify-content: center;
    }

    .email-subject-preview {
        max-width: 130px;
    }
}

@media (max-width: 400px) {
    .email-metrics-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
function openEmailPreview(key, name, previewUrl, sendUrl) {
    const modal = document.getElementById('emailPreviewModal');
    const title = document.getElementById('previewModalTitle');
    const iframe = document.getElementById('emailPreviewIframe');
    const form = document.getElementById('modalSendTestForm');

    title.textContent = name;
    iframe.src = previewUrl;
    form.action = sendUrl;

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeEmailPreview() {
    const modal = document.getElementById('emailPreviewModal');
    const iframe = document.getElementById('emailPreviewIframe');
    iframe.src = 'about:blank';
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

function handleBackdropClick(e) {
    if (e.target.id === 'emailPreviewModal') {
        closeEmailPreview();
    }
}

function setPreviewWidth(mode) {
    const wrapper = document.querySelector('.email-iframe-wrapper');
    const btnDesktop = document.getElementById('btnDesktopView');
    const btnMobile = document.getElementById('btnMobileView');

    if (mode === 'mobile') {
        wrapper.classList.add('mobile-view');
        btnMobile.classList.add('active');
        btnDesktop.classList.remove('active');
    } else {
        wrapper.classList.remove('mobile-view');
        btnDesktop.classList.add('active');
        btnMobile.classList.remove('active');
    }
}

function openSendTestModal(key, name, sendUrl) {
    openEmailPreview(key, name, `/admin/emails/${key}/preview`, sendUrl);
}

function filterEmailTemplates() {
    const query = document.getElementById('emailSearchInput').value.toLowerCase().trim();
    const cards = document.querySelectorAll('.email-tpl-card');
    let count = 0;

    cards.forEach(card => {
        const searchText = card.getAttribute('data-search');
        const activeCategory = document.querySelector('.filter-tab.active').textContent.trim();
        const matchesQuery = searchText.includes(query);

        if (matchesQuery && (activeCategory.startsWith('All') || matchesCategory(card, activeCategory))) {
            card.style.display = 'flex';
            count++;
        } else {
            card.style.display = 'none';
        }
    });

    document.getElementById('noResultsState').style.display = count === 0 ? 'block' : 'none';
}

function filterCategory(category, tabBtn) {
    document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active'));
    tabBtn.classList.add('active');

    const query = document.getElementById('emailSearchInput').value.toLowerCase().trim();
    const cards = document.querySelectorAll('.email-tpl-card');
    let count = 0;

    cards.forEach(card => {
        const searchText = card.getAttribute('data-search');
        const matchesCategoryName = category === 'all' || matchesCategory(card, category);
        const matchesQuery = searchText.includes(query);

        if (matchesCategoryName && matchesQuery) {
            card.style.display = 'flex';
            count++;
        } else {
            card.style.display = 'none';
        }
    });

    document.getElementById('noResultsState').style.display = count === 0 ? 'block' : 'none';
}

function matchesCategory(card, catKey) {
    const cardCat = card.getAttribute('data-category');
    if (catKey === 'Authentication' && cardCat === 'Customer Authentication') return true;
    if (catKey === 'Admin Notifications' && cardCat === 'Admin Notifications') return true;
    if (catKey === 'Customer Status' && cardCat === 'Customer Status') return true;
    if (catKey === 'Commercial & Trading' && (cardCat === 'Commercial & Trading' || cardCat === 'Orders & Fulfillment')) return true;
    return false;
}

// Close modal on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeEmailPreview();
});
</script>
@endpush
@endsection
