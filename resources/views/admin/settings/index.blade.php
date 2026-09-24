@extends('layouts.admin')
@section('title', 'Settings Management — Admin')

@section('content')
<!-- Top Page Header with Breadcrumb -->
<div class="admin-topbar" style="margin-bottom:var(--space-5)">
    <div>
        <h1 class="admin-page-title" style="font-size:1.5rem">Settings Management</h1>
        <p class="text-sm text-muted">Configure general store identity, SEO metadata, SMTP email service, and application modules</p>
    </div>
    <div style="font-size:0.85rem;color:var(--gray-500)">
        <a href="{{ route('admin.dashboard') }}" style="color:var(--gray-500);text-decoration:none">Dashboard</a>
        <span style="margin:0 4px">›</span>
        <span style="color:var(--gray-900);font-weight:600">Settings</span>
    </div>
</div>

@php
    $tabs = [
        ['id' => 'general',    'icon' => '⚙️', 'label' => 'General Settings'],
        ['id' => 'contact',    'icon' => '📍', 'label' => 'Contact Page'],
        ['id' => 'smtp',       'icon' => '✉️', 'label' => 'SMTP Settings'],
        ['id' => 'payment',    'icon' => '💳', 'label' => 'Payment Integrations'],
        ['id' => 'currency',   'icon' => '💱', 'label' => 'Currency & Exchange'],
        ['id' => 'modules',    'icon' => '🧩', 'label' => 'Modules Settings'],
        ['id' => 'order',      'icon' => '🛒', 'label' => 'Order Settings'],
        ['id' => 'tracking',   'icon' => '📊', 'label' => 'Website Tracking'],
        ['id' => 'appearance', 'icon' => '🎨', 'label' => 'Site Appearance'],
        ['id' => 'recaptcha',  'icon' => '🛡️', 'label' => 'reCAPTCHA Settings'],
        ['id' => 'keys',       'icon' => '🔑', 'label' => 'Site Keys'],
        ['id' => 'database',   'icon' => '💾', 'label' => 'Database Backup'],
    ];
@endphp

<!-- Mobile Horizontal Scrollable Tab Bar (Visible on <= 860px) -->
<div class="settings-mobile-tabs-container">
    <div class="settings-mobile-tabs-scroll">
        @foreach($tabs as $t)
        <a href="#{{ $t['id'] }}" onclick="switchSettingsTab('{{ $t['id'] }}', event)" 
           class="settings-mobile-tab-pill {{ (request('tab', $activeTab) === $t['id']) ? 'active' : '' }}" 
           data-tab-target="{{ $t['id'] }}">
            <span>{{ $t['icon'] }}</span>
            <span>{{ $t['label'] }}</span>
        </a>
        @endforeach
    </div>
</div>

<!-- Main 2-Column Settings Layout -->
<div style="display:grid;grid-template-columns:260px 1fr;gap:var(--space-6);align-items:start" class="settings-layout-grid">

    <!-- Left Sidebar: Main Settings Menu -->
    <div class="card settings-sidebar-card" style="padding:var(--space-4);background:white;border-radius:12px;border:1px solid var(--gray-200);box-shadow:0 1px 3px rgba(0,0,0,0.05)">
        <div style="font-size:0.75rem;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px">
            Main Settings
        </div>

        <!-- Filter / Search box -->
        <div style="margin-bottom:14px">
            <input type="text" id="settingsMenuSearch" placeholder="Search..." onkeyup="filterSettingsMenu(this.value)"
                   style="width:100%;height:36px;font-size:0.85rem;border:1px solid var(--gray-300);border-radius:6px;padding:0 12px;outline:none">
        </div>

        <!-- Menu Navigation Items -->
        <nav id="settingsNavList" style="display:flex;flex-direction:column;gap:4px">
            @foreach($tabs as $t)
            <a href="#{{ $t['id'] }}" onclick="switchSettingsTab('{{ $t['id'] }}', event)" 
               class="settings-nav-item {{ (request('tab', $activeTab) === $t['id']) ? 'active' : '' }}" 
               data-tab-target="{{ $t['id'] }}"
               style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;font-size:0.85rem;font-weight:600;text-decoration:none;transition:all 0.15s;color:var(--gray-700)">
                <span style="font-size:1.1rem;line-height:1">{{ $t['icon'] }}</span>
                <span>{{ $t['label'] }}</span>
            </a>
            @endforeach
        </nav>
    </div>

    <!-- Right Column: Settings Content Panes -->
    <div style="min-width:0">

        <!-- ================= TAB 1: GENERAL SETTINGS ================= -->
        <div id="tab-general" class="settings-pane card" style="background:white;border-radius:12px;border:1px solid var(--gray-200);box-shadow:0 1px 3px rgba(0,0,0,0.05);overflow:hidden">
            <div style="padding:16px 22px;border-bottom:1px solid var(--gray-200);background:#fafafa">
                <h2 style="font-size:1.05rem;font-weight:800;color:var(--gray-900);margin:0">General Settings</h2>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" style="padding:22px">
                @csrf
                <input type="hidden" name="tab" value="general">

                <!-- Site Name -->
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">Site Name <span class="required">*</span></label>
                    <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $settings['site_name'] ?? '') }}" required>
                </div>

                <!-- Site Description -->
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">Site Description</label>
                    <textarea name="site_description" class="form-control" rows="3">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                </div>


                <!-- Meta Keywords -->
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $settings['meta_keywords'] ?? '') }}" placeholder="seafood, salmon, prawns, b2b, wholesale">
                    <div style="font-size:0.75rem;color:var(--gray-500);margin-top:4px">Separate keywords with commas.</div>
                </div>

                <!-- Canonical Tag / URL -->
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">Canonical Tag / URL</label>
                    <input type="url" name="canonical_url" class="form-control" value="{{ old('canonical_url', $settings['canonical_url'] ?? '') }}" placeholder="https://example.com/current-page">
                    <div style="font-size:0.75rem;color:var(--gray-500);margin-top:4px">Add primary canonical URL to protect SEO rank.</div>
                </div>

                <!-- Header Tags (inside <head>) -->
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">Header Tags (inside &lt;head&gt;)</label>
                    <textarea name="header_tags" class="form-control" rows="3" style="font-family:monospace;font-size:0.8rem" placeholder="Paste meta/script/link tags">{{ old('header_tags', $settings['header_tags'] ?? '') }}</textarea>
                </div>

                <!-- Footer Tags (before </body>) -->
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">Footer Tags (before &lt;/body&gt;)</label>
                    <textarea name="footer_tags" class="form-control" rows="3" style="font-family:monospace;font-size:0.8rem" placeholder="Paste script/javascript tags">{{ old('footer_tags', $settings['footer_tags'] ?? '') }}</textarea>
                </div>

                <!-- Schema Markup (JSON-LD) -->
                <div class="form-group mb-6">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">Schema Markup (JSON-LD)</label>
                    <textarea name="schema_markup" class="form-control" rows="4" style="font-family:monospace;font-size:0.8rem" placeholder="<script type=&quot;application/ld+json&quot;>">{{ old('schema_markup', $settings['schema_markup'] ?? '') }}</textarea>
                </div>

                <div style="display:flex;justify-content:flex-end">
                    <button type="submit" class="btn btn-primary" style="background:#5b5bf0;border-color:#5b5bf0;padding:10px 24px;font-weight:700">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- ================= TAB: CONTACT PAGE ================= -->
        <div id="tab-contact" class="settings-pane card" style="display:none;background:white;border-radius:14px;border:1.5px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);overflow:hidden">
            <div style="padding:18px 24px;border-bottom:1.5px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                <div>
                    <h2 style="font-size:1.15rem;font-weight:800;color:#0f172a;margin:0;display:flex;align-items:center;gap:8px">
                        <span>📍</span> Contact Page Settings
                    </h2>
                    <p style="font-size:0.82rem;color:#64748b;margin:4px 0 0">
                        Real-time configuration for Contact Us page, storefront Footer, WhatsApp direct chat, and Order Invoices.
                    </p>
                </div>
                <span style="font-size:0.75rem;font-weight:700;color:#059669;background:#ecfdf5;padding:4px 12px;border-radius:20px;border:1px solid #a7f3d0">
                    ● Live Storefront Synced
                </span>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" style="padding:clamp(16px, 3vw, 24px)" id="contactSettingsForm">
                @csrf
                <input type="hidden" name="tab" value="contact">

                {{-- Section: Company Identity --}}
                <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
                    <div style="font-size:0.75rem;font-weight:800;color:#4f46e5;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;display:flex;align-items:center;gap:6px">
                        <span>🏢</span> Company Identity
                    </div>

                    <div class="settings-form-grid-3">
                        <div class="form-group">
                            <label class="form-label" style="font-weight:700;color:#1e293b;font-size:0.875rem;margin-bottom:6px">Company / Store Name (EN)</label>
                            <input type="text" name="store_name" id="input_store_name" class="form-control settings-input"
                                   value="{{ old('store_name', $settings['store_name'] ?? '') }}"
                                   placeholder="MST Import & Export Sdn. Bhd."
                                   style="border-radius:10px;height:42px">
                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px">Used in footer copyright, contact cards, and invoices</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight:700;color:#1e293b;font-size:0.875rem;margin-bottom:6px">Company Name (Chinese)</label>
                            <input type="text" name="store_company_zh" class="form-control settings-input"
                                   value="{{ old('store_company_zh', $settings['store_company_zh'] ?? '') }}"
                                   placeholder="镁嘉国际贸易有限公司"
                                   style="border-radius:10px;height:42px">
                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px">Shown in Contact Facility card & header</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight:700;color:#1e293b;font-size:0.875rem;margin-bottom:6px">Store Tagline / Slogan</label>
                            <input type="text" name="store_tagline" class="form-control settings-input"
                                   value="{{ old('store_tagline', $settings['store_tagline'] ?? '') }}"
                                   placeholder="Flow with Integrity, Grow with Strength"
                                   style="border-radius:10px;height:42px">
                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px">Shown in the footer brand description</div>
                        </div>
                    </div>
                </div>

                {{-- Section: Phone Numbers --}}
                <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
                    <div style="font-size:0.75rem;font-weight:800;color:#4f46e5;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;display:flex;align-items:center;gap:6px">
                        <span>📞</span> Phone Numbers
                    </div>

                    <div class="settings-form-grid-3">
                        <div class="form-group">
                            <label class="form-label" style="font-weight:700;color:#1e293b;font-size:0.875rem;margin-bottom:6px">Primary Phone</label>
                            <input type="text" name="store_phone" id="input_store_phone" class="form-control settings-input"
                                   value="{{ old('store_phone', $settings['store_phone'] ?? '') }}"
                                   placeholder="013-2800168"
                                   style="border-radius:10px;height:42px">
                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px">Main clickable tel: link</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight:700;color:#1e293b;font-size:0.875rem;margin-bottom:6px">Phone 2 <span style="font-weight:400;color:#94a3b8">(optional)</span></label>
                            <input type="text" name="store_phone_2" class="form-control settings-input"
                                   value="{{ old('store_phone_2', $settings['store_phone_2'] ?? '') }}"
                                   placeholder="011-4360109"
                                   style="border-radius:10px;height:42px">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight:700;color:#1e293b;font-size:0.875rem;margin-bottom:6px">Phone 3 <span style="font-weight:400;color:#94a3b8">(optional)</span></label>
                            <input type="text" name="store_phone_3" class="form-control settings-input"
                                   value="{{ old('store_phone_3', $settings['store_phone_3'] ?? '') }}"
                                   placeholder="011-2710260"
                                   style="border-radius:10px;height:42px">
                        </div>
                    </div>
                    <div style="font-size:0.75rem;color:#64748b;margin-top:8px;background:#f8fafc;padding:8px 12px;border-radius:8px;border-left:3px solid #4f46e5">
                        💡 All filled phone numbers will be rendered as clickable tel: links on the Contact Us page and in the storefront footer.
                    </div>
                </div>

                {{-- Section: Email Addresses --}}
                <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
                    <div style="font-size:0.75rem;font-weight:800;color:#4f46e5;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;display:flex;align-items:center;gap:6px">
                        <span>✉️</span> Email Addresses
                    </div>

                    <div class="settings-form-grid-2">
                        <div class="form-group">
                            <label class="form-label" style="font-weight:700;color:#1e293b;font-size:0.875rem;margin-bottom:6px">General / Support Email</label>
                            <input type="email" name="store_email" id="input_store_email" class="form-control settings-input"
                                   value="{{ old('store_email', $settings['store_email'] ?? '') }}"
                                   placeholder="mikatrading15@gmail.com"
                                   style="border-radius:10px;height:42px">
                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px">Shown as "General Support" email on Contact page</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight:700;color:#1e293b;font-size:0.875rem;margin-bottom:6px">B2B / Wholesale Email <span style="font-weight:400;color:#94a3b8">(optional)</span></label>
                            <input type="email" name="store_wholesale_email" class="form-control settings-input"
                                   value="{{ old('store_wholesale_email', $settings['store_wholesale_email'] ?? '') }}"
                                   placeholder="wholesale@company.com"
                                   style="border-radius:10px;height:42px">
                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px">Shown as "B2B / Wholesale" email. Leave blank to hide.</div>
                        </div>
                    </div>
                </div>

                {{-- Section: WhatsApp --}}
                <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
                    <div style="font-size:0.75rem;font-weight:800;color:#4f46e5;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;display:flex;align-items:center;gap:6px">
                        <span>💬</span> WhatsApp Direct Contact
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="font-weight:700;color:#1e293b;font-size:0.875rem;margin-bottom:6px">WhatsApp Chat URL</label>
                        <input type="url" name="social_whatsapp" class="form-control settings-input"
                               value="{{ old('social_whatsapp', $settings['social_whatsapp'] ?? '') }}"
                               placeholder="https://wa.me/60132800168"
                               style="border-radius:10px;height:42px">
                        <div style="font-size:0.75rem;color:#64748b;margin-top:6px">
                            Format: <code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;color:#0f766e;font-weight:600">https://wa.me/60XXXXXXXXXX</code> (country code without +, no dashes). Leave blank to hide the WhatsApp button.
                        </div>
                    </div>
                </div>

                {{-- Section: Physical Address & Map --}}
                <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
                    <div style="font-size:0.75rem;font-weight:800;color:#4f46e5;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;display:flex;align-items:center;gap:6px">
                        <span>📍</span> Physical Store Address &amp; Location
                    </div>

                    <div class="form-group" style="margin-bottom:16px">
                        <label class="form-label" style="font-weight:700;color:#1e293b;font-size:0.875rem;margin-bottom:6px">Store Address</label>
                        <textarea name="store_address" id="input_store_address" class="form-control settings-input" rows="3"
                                  placeholder="7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia"
                                  style="border-radius:10px;line-height:1.55">{{ old('store_address', $settings['store_address'] ?? '') }}</textarea>
                        <div style="font-size:0.75rem;color:#64748b;margin-top:4px">Displayed in storefront footer, contact page address card, and invoices</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="font-weight:700;color:#1e293b;font-size:0.875rem;margin-bottom:6px">Google Maps URL <span style="font-weight:400;color:#94a3b8">(optional)</span></label>
                        <input type="url" name="store_map_url" class="form-control settings-input"
                               value="{{ old('store_map_url', $settings['store_map_url'] ?? '') }}"
                               placeholder="https://maps.app.goo.gl/..."
                               style="border-radius:10px;height:42px">
                        <div style="font-size:0.75rem;color:#64748b;margin-top:4px">If filled, a "📌 View on Google Maps" button appears below the address on Contact page.</div>
                    </div>
                </div>

                {{-- Section: Operating Hours --}}
                <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
                    <div style="font-size:0.75rem;font-weight:800;color:#4f46e5;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;display:flex;align-items:center;gap:6px">
                        <span>🕐</span> Operating Hours
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="font-weight:700;color:#1e293b;font-size:0.875rem;margin-bottom:6px">Operating Hours Text</label>
                        <input type="text" name="store_hours" id="input_store_hours" class="form-control settings-input"
                               value="{{ old('store_hours', $settings['store_hours'] ?? '') }}"
                               placeholder="Monday – Saturday: 8:00am – 6:00pm (Sunday & Public Holidays: Closed)"
                               style="border-radius:10px;height:42px">
                        <div style="font-size:0.75rem;color:#64748b;margin-top:4px">Free text — displayed in the Operating Hours card on the Contact page and in the footer</div>
                    </div>
                </div>

                {{-- Live Preview Card --}}
                <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;padding:18px;margin-bottom:24px">
                    <div style="font-size:0.75rem;font-weight:800;color:#4f46e5;margin-bottom:12px;text-transform:uppercase;letter-spacing:0.8px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:6px">
                        <span>👁 Live Preview — Storefront Contact Cards</span>
                        <span style="font-size:0.7rem;font-weight:600;color:#64748b;text-transform:none">Updates live as you type</span>
                    </div>
                    <div class="contact-preview-grid">
                        <div class="contact-preview-item">
                            <span class="contact-preview-icon">📍</span>
                            <div style="min-width:0">
                                <div class="contact-preview-label">Store Address</div>
                                <div class="contact-preview-value" id="preview-address">{{ $settings['store_address'] ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="contact-preview-item">
                            <span class="contact-preview-icon">📞</span>
                            <div style="min-width:0">
                                <div class="contact-preview-label">Primary Phone</div>
                                <div class="contact-preview-value blue" id="preview-phone">{{ $settings['store_phone'] ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="contact-preview-item">
                            <span class="contact-preview-icon">✉️</span>
                            <div style="min-width:0">
                                <div class="contact-preview-label">Support Email</div>
                                <div class="contact-preview-value blue" id="preview-email">{{ $settings['store_email'] ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="contact-preview-item">
                            <span class="contact-preview-icon">🕐</span>
                            <div style="min-width:0">
                                <div class="contact-preview-label">Operating Hours</div>
                                <div class="contact-preview-value" id="preview-hours">{{ $settings['store_hours'] ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-save-actions" style="display:flex;justify-content:flex-end">
                    <button type="submit" class="btn btn-primary settings-contact-save-btn" style="background:#4f46e5;border-color:#4f46e5;padding:11px 32px;font-weight:700;font-size:0.95rem;border-radius:10px;box-shadow:0 2px 6px rgba(79,70,229,0.25);display:inline-flex;align-items:center;gap:8px">
                        <span>💾</span> Save Contact Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- ================= TAB 2: SMTP SETTINGS ================= -->
        <div id="tab-smtp" class="settings-pane card" style="display:none;background:white;border-radius:14px;border:1.5px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);overflow:hidden">

            {{-- Tab Header --}}
            <div style="padding:18px 24px;border-bottom:1.5px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                <div>
                    <h2 style="font-size:1.15rem;font-weight:800;color:#0f172a;margin:0;display:flex;align-items:center;gap:8px">
                        <span>✉️</span> SMTP Email Settings
                    </h2>
                    <p style="font-size:0.82rem;color:#64748b;margin:4px 0 0">Configure your outbound email server for order confirmations, contact inquiries, and system notifications.</p>
                </div>
                <span id="smtp-status-badge" style="font-size:0.75rem;font-weight:700;color:#059669;background:#ecfdf5;padding:4px 12px;border-radius:20px;border:1px solid #a7f3d0">
                    ● SMTP Configured
                </span>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" style="padding:clamp(16px,3vw,24px)" id="smtpSettingsForm">
                @csrf
                <input type="hidden" name="tab" value="smtp">

                {{-- Section: Server Configuration --}}
                <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
                    <div style="font-size:0.75rem;font-weight:800;color:#4f46e5;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;display:flex;align-items:center;gap:6px">
                        <span>🌐</span> Server Configuration
                    </div>
                    <div class="settings-form-grid-2">
                        {{-- Mail Mailer --}}
                        <div class="form-group">
                            <label class="form-label smtp-label">Mail Driver / Mailer</label>
                            <select name="mail_mailer" class="form-control settings-input" style="border-radius:10px;height:42px">
                                <option value="smtp" {{ old('mail_mailer', $settings['mail_mailer'] ?? 'smtp') === 'smtp' ? 'selected' : '' }}>smtp</option>
                                <option value="sendmail" {{ old('mail_mailer', $settings['mail_mailer'] ?? '') === 'sendmail' ? 'selected' : '' }}>sendmail</option>
                                <option value="mailgun" {{ old('mail_mailer', $settings['mail_mailer'] ?? '') === 'mailgun' ? 'selected' : '' }}>mailgun</option>
                                <option value="log" {{ old('mail_mailer', $settings['mail_mailer'] ?? '') === 'log' ? 'selected' : '' }}>log (dev only)</option>
                            </select>
                            <div class="smtp-hint">Usually <code class="smtp-code">smtp</code> for Gmail, SendGrid, or Mailgun</div>
                        </div>

                        {{-- Mail Host --}}
                        <div class="form-group">
                            <label class="form-label smtp-label">SMTP Host</label>
                            <input type="text" name="mail_host" class="form-control settings-input" style="border-radius:10px;height:42px"
                                   value="{{ old('mail_host', $settings['mail_host'] ?? 'smtp.gmail.com') }}"
                                   placeholder="smtp.gmail.com">
                            <div class="smtp-hint">e.g. <code class="smtp-code">smtp.gmail.com</code>, <code class="smtp-code">smtp.sendgrid.net</code></div>
                        </div>

                        {{-- Mail Port --}}
                        <div class="form-group">
                            <label class="form-label smtp-label">SMTP Port</label>
                            <input type="number" name="mail_port" class="form-control settings-input" style="border-radius:10px;height:42px"
                                   value="{{ old('mail_port', $settings['mail_port'] ?? '465') }}"
                                   placeholder="465">
                            <div class="smtp-hint">SSL → <code class="smtp-code">465</code> &nbsp;|&nbsp; TLS → <code class="smtp-code">587</code></div>
                        </div>

                        {{-- Mail Encryption --}}
                        <div class="form-group">
                            <label class="form-label smtp-label">Encryption Protocol</label>
                            <select name="mail_encryption" class="form-control settings-input" style="border-radius:10px;height:42px">
                                <option value="SSL" {{ old('mail_encryption', $settings['mail_encryption'] ?? '') === 'SSL' ? 'selected' : '' }}>🔒 SSL</option>
                                <option value="TLS" {{ old('mail_encryption', $settings['mail_encryption'] ?? '') === 'TLS' ? 'selected' : '' }}>🔐 TLS (STARTTLS)</option>
                                <option value="None" {{ old('mail_encryption', $settings['mail_encryption'] ?? '') === 'None' ? 'selected' : '' }}>⚠️ None (not recommended)</option>
                            </select>
                            <div class="smtp-hint">Use SSL or TLS for secure outgoing mail</div>
                        </div>
                    </div>
                </div>

                {{-- Section: Authentication --}}
                <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
                    <div style="font-size:0.75rem;font-weight:800;color:#4f46e5;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;display:flex;align-items:center;gap:6px">
                        <span>🔑</span> Authentication Credentials
                    </div>
                    <div class="settings-form-grid-2">
                        {{-- Mail Username --}}
                        <div class="form-group">
                            <label class="form-label smtp-label">SMTP Username</label>
                            <input type="text" name="mail_username" id="smtp_username" class="form-control settings-input" style="border-radius:10px;height:42px"
                                   value="{{ old('mail_username', $settings['mail_username'] ?? '') }}"
                                   placeholder="your@gmail.com" autocomplete="username">
                            <div class="smtp-hint">Your Gmail / SMTP account email address</div>
                        </div>

                        {{-- Mail Password --}}
                        <div class="form-group">
                            <label class="form-label smtp-label">SMTP Password / App Password</label>
                            <div style="position:relative">
                                <input type="password" name="mail_password" id="smtp_password" class="form-control settings-input" style="border-radius:10px;height:42px;padding-right:44px"
                                       placeholder="••••••••••••"
                                       value="{{ old('mail_password', $settings['mail_password'] ?? '') }}"
                                       autocomplete="current-password">
                                <button type="button" onclick="toggleSmtpPassword()" title="Show / Hide password"
                                        style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:1.1rem;color:#64748b;padding:2px 4px;line-height:1"
                                        id="smtp-eye-btn">👁</button>
                            </div>
                            <div class="smtp-hint">For Gmail, use a <a href="https://myaccount.google.com/apppasswords" target="_blank" style="color:#4f46e5;font-weight:600;text-decoration:none">16-character App Password</a>, not your account password</div>
                        </div>
                    </div>

                    {{-- Gmail App Password Info Box --}}
                    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:12px 16px;display:flex;gap:12px;align-items:flex-start;margin-top:4px">
                        <span style="font-size:1.2rem;flex-shrink:0;margin-top:1px">ℹ️</span>
                        <div style="font-size:0.8rem;color:#1e40af;line-height:1.5">
                            <strong>Gmail users:</strong> Go to <em>Google Account → Security → 2-Step Verification → App Passwords</em>.
                            Generate a new App Password for "Mail" and paste it above. Your regular password will <strong>not</strong> work here.
                        </div>
                    </div>
                </div>

                {{-- Section: Sender Identity --}}
                <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
                    <div style="font-size:0.75rem;font-weight:800;color:#4f46e5;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;display:flex;align-items:center;gap:6px">
                        <span>📧</span> Sender Identity
                    </div>
                    <div class="settings-form-grid-2">
                        {{-- Mail From Address --}}
                        <div class="form-group">
                            <label class="form-label smtp-label">From Email Address</label>
                            <input type="email" name="mail_from_address" class="form-control settings-input" style="border-radius:10px;height:42px"
                                   value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}"
                                   placeholder="no-reply@company.com">
                            <div class="smtp-hint">The "From:" address recipients see in their inbox</div>
                        </div>

                        {{-- Mail From Name --}}
                        <div class="form-group">
                            <label class="form-label smtp-label">From Display Name</label>
                            <input type="text" name="mail_from_name" class="form-control settings-input" style="border-radius:10px;height:42px"
                                   value="{{ old('mail_from_name', $settings['mail_from_name'] ?? '') }}"
                                   placeholder="Mika Import and Export SDN Bhd">
                            <div class="smtp-hint">The sender name shown in the email client</div>
                        </div>
                    </div>
                </div>

                {{-- Section: Contact / Notification Emails --}}
                <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
                    <div style="font-size:0.75rem;font-weight:800;color:#4f46e5;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;display:flex;align-items:center;gap:6px">
                        <span>📬</span> Notification Recipients
                    </div>
                    <div class="settings-form-grid-2">
                        {{-- Primary Contact Email --}}
                        <div class="form-group">
                            <label class="form-label smtp-label">Primary Admin Email</label>
                            <input type="email" name="mail_contact_email" class="form-control settings-input" style="border-radius:10px;height:42px"
                                   value="{{ old('mail_contact_email', $settings['mail_contact_email'] ?? ($settings['store_email'] ?? '')) }}"
                                   placeholder="admin@company.com">
                            <div class="smtp-hint">Receives order alerts and contact form inquiries</div>
                        </div>

                        {{-- Secondary Email --}}
                        <div class="form-group">
                            <label class="form-label smtp-label">Secondary / CC Email <span style="font-weight:400;color:#94a3b8">(optional)</span></label>
                            <input type="email" name="mail_secondary_email" class="form-control settings-input" style="border-radius:10px;height:42px"
                                   value="{{ old('mail_secondary_email', $settings['mail_secondary_email'] ?? '') }}"
                                   placeholder="accounts@company.com">
                            <div class="smtp-hint">CC'd on contact inquiries. Leave blank to skip</div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="smtp-action-row">
                    <div class="smtp-action-left">
                        <button type="reset" class="btn btn-secondary smtp-btn-reset" style="padding:10px 20px;border-radius:10px;font-weight:600">
                            ↺ Reset
                        </button>
                    </div>
                    <div class="smtp-action-right">
                        <button type="button" class="btn smtp-btn-test" onclick="openTestEmailModal()" style="padding:10px 20px;border-radius:10px;font-weight:700;background:#eff6ff;border:1.5px solid #bfdbfe;color:#2563eb">
                            🧪 Send Test Email
                        </button>
                        <button type="submit" class="btn btn-primary smtp-btn-save" style="background:#4f46e5;border-color:#4f46e5;padding:10px 28px;font-weight:700;font-size:0.95rem;border-radius:10px;box-shadow:0 2px 6px rgba(79,70,229,0.25);display:inline-flex;align-items:center;gap:8px">
                            <span>💾</span> Save SMTP Settings
                        </button>
                    </div>
                </div>
        </div>

        <!-- ================= TAB: PAYMENT INTEGRATIONS & STRIPE GATEWAY ================= -->
        <div id="tab-payment" class="settings-pane card" style="display:none;background:white;border-radius:14px;border:1.5px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);overflow:hidden">
            {{-- Tab Header --}}
            <div style="padding:18px 24px;border-bottom:1.5px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
                <div>
                    <h2 style="font-size:1.15rem;font-weight:800;color:#0f172a;margin:0;display:flex;align-items:center;gap:8px">
                        <span>💳</span> Payment Integrations &amp; Stripe Gateway
                    </h2>
                    <p style="font-size:0.82rem;color:#64748b;margin:4px 0 0">Configure Stripe payment gateway, toggle between Test (Sandbox) and Live (Production) modes, and manage API keys.</p>
                </div>

                <div style="display:flex;align-items:center;gap:10px">
                    @php
                        $stripeMode = $settings['stripe_mode'] ?? 'test';
                        $stripeEnabled = ($settings['stripe_enabled'] ?? '1') == '1';
                    @endphp
                    @if($stripeEnabled)
                        @if($stripeMode === 'live')
                        <span style="font-size:0.8rem;font-weight:800;color:#166534;background:#f0fdf4;padding:6px 14px;border-radius:20px;border:1px solid #bbf7d0;display:inline-flex;align-items:center;gap:6px">
                            <span style="width:8px;height:8px;background:#22c55e;border-radius:50%;display:inline-block"></span> Live Mode Active
                        </span>
                        @else
                        <span style="font-size:0.8rem;font-weight:800;color:#b45309;background:#fffbeb;padding:6px 14px;border-radius:20px;border:1px solid #fde68a;display:inline-flex;align-items:center;gap:6px">
                            <span style="width:8px;height:8px;background:#f59e0b;border-radius:50%;display:inline-block"></span> Test Mode (Sandbox)
                        </span>
                        @endif
                    @else
                    <span style="font-size:0.8rem;font-weight:700;color:#64748b;background:#f1f5f9;padding:6px 14px;border-radius:20px;border:1px solid #cbd5e1">
                        ⏸️ Stripe Disabled
                    </span>
                    @endif
                </div>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" style="padding:clamp(16px,3vw,24px)">
                @csrf
                <input type="hidden" name="tab" value="payment">

                <!-- Stripe Enable / Disable Switch -->
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
                    <div>
                        <div style="font-weight:700;color:#0f172a;font-size:0.95rem">Enable Stripe Credit Card &amp; Online Payments</div>
                        <div style="font-size:0.8rem;color:#64748b;margin-top:2px">When enabled, customers can pay using credit/debit cards &amp; FPX online banking during checkout.</div>
                    </div>
                    <label class="switch-toggle" title="Toggle Stripe Payments">
                        <input type="checkbox" name="stripe_enabled" value="1" {{ old('stripe_enabled', $settings['stripe_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>

                <!-- Environment Selection: Test vs Live -->
                <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
                    <div style="font-size:0.75rem;font-weight:800;color:#2563eb;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:12px;display:flex;align-items:center;gap:6px">
                        <span>⚡</span> Gateway Environment Mode
                    </div>

                    <div style="display:grid;grid-template-columns:repeat(2, 1fr);gap:16px" class="settings-form-grid-2">
                        <!-- Test Mode Radio -->
                        <label id="labelStripeModeTest" style="border:2px solid {{ ($settings['stripe_mode'] ?? 'test') === 'test' ? '#2563eb' : '#e2e8f0' }};background:{{ ($settings['stripe_mode'] ?? 'test') === 'test' ? '#eff6ff' : '#ffffff' }};border-radius:12px;padding:16px;cursor:pointer;display:flex;align-items:flex-start;gap:12px;transition:all 0.15s ease">
                            <input type="radio" name="stripe_mode" value="test" {{ old('stripe_mode', $settings['stripe_mode'] ?? 'test') === 'test' ? 'checked' : '' }} style="margin-top:3px" onchange="toggleStripeModeVisual('test')">
                            <div>
                                <div style="font-weight:800;color:#1e40af;font-size:0.92rem">🧪 Test Mode (Sandbox)</div>
                                <div style="font-size:0.78rem;color:#64748b;margin-top:3px;line-height:1.4">Use test card numbers (e.g. 4242...) for testing without real charges.</div>
                            </div>
                        </label>

                        <!-- Live Mode Radio -->
                        <label id="labelStripeModeLive" style="border:2px solid {{ ($settings['stripe_mode'] ?? 'test') === 'live' ? '#16a34a' : '#e2e8f0' }};background:{{ ($settings['stripe_mode'] ?? 'test') === 'live' ? '#f0fdf4' : '#ffffff' }};border-radius:12px;padding:16px;cursor:pointer;display:flex;align-items:flex-start;gap:12px;transition:all 0.15s ease">
                            <input type="radio" name="stripe_mode" value="live" {{ old('stripe_mode', $settings['stripe_mode'] ?? 'test') === 'live' ? 'checked' : '' }} style="margin-top:3px" onchange="toggleStripeModeVisual('live')">
                            <div>
                                <div style="font-weight:800;color:#15803d;font-size:0.92rem">🚀 Live Mode (Production)</div>
                                <div style="font-size:0.78rem;color:#64748b;margin-top:3px;line-height:1.4">Process real customer transactions via your Stripe merchant account.</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Test Mode Credentials Section -->
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:20px;margin-bottom:24px">
                    <div style="font-size:0.82rem;font-weight:800;color:#1e40af;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;display:flex;align-items:center;gap:8px">
                        <span>🧪</span> Test API Credentials (pk_test_... &amp; sk_test_...)
                    </div>

                    <div class="settings-form-grid-2">
                        <!-- Test Publishable Key -->
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:700;color:#334155">Test Publishable Key</label>
                            <input type="text" name="stripe_test_key" class="form-control" style="border-radius:10px;font-family:monospace;font-size:0.85rem"
                                   value="{{ old('stripe_test_key', $settings['stripe_test_key'] ?? '') }}" placeholder="pk_test_51Nx...">
                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px">Starts with <code>pk_test_</code></div>
                        </div>

                        <!-- Test Secret Key -->
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:700;color:#334155">Test Secret Key</label>
                            <div style="position:relative">
                                <input type="password" id="stripe_test_secret" name="stripe_test_secret" class="form-control" style="border-radius:10px;font-family:monospace;font-size:0.85rem;padding-right:40px"
                                       value="{{ old('stripe_test_secret', $settings['stripe_test_secret'] ?? '') }}" placeholder="sk_test_51Nx... (leave blank to keep current)">
                                <button type="button" onclick="togglePasswordVisibility('stripe_test_secret')" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);border:none;background:transparent;cursor:pointer;color:#64748b">👁️</button>
                            </div>
                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px">Starts with <code>sk_test_</code></div>
                        </div>
                    </div>
                </div>

                <!-- Live Mode Credentials Section -->
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:20px;margin-bottom:24px">
                    <div style="font-size:0.82rem;font-weight:800;color:#15803d;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;display:flex;align-items:center;gap:8px">
                        <span>🚀</span> Live API Credentials (pk_live_... &amp; sk_live_...)
                    </div>

                    <div class="settings-form-grid-2">
                        <!-- Live Publishable Key -->
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:700;color:#334155">Live Publishable Key</label>
                            <input type="text" name="stripe_live_key" class="form-control" style="border-radius:10px;font-family:monospace;font-size:0.85rem"
                                   value="{{ old('stripe_live_key', $settings['stripe_live_key'] ?? '') }}" placeholder="pk_live_51Nx...">
                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px">Starts with <code>pk_live_</code></div>
                        </div>

                        <!-- Live Secret Key -->
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:700;color:#334155">Live Secret Key</label>
                            <div style="position:relative">
                                <input type="password" id="stripe_live_secret" name="stripe_live_secret" class="form-control" style="border-radius:10px;font-family:monospace;font-size:0.85rem;padding-right:40px"
                                       value="{{ old('stripe_live_secret', $settings['stripe_live_secret'] ?? '') }}" placeholder="sk_live_51Nx... (leave blank to keep current)">
                                <button type="button" onclick="togglePasswordVisibility('stripe_live_secret')" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);border:none;background:transparent;cursor:pointer;color:#64748b">👁️</button>
                            </div>
                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px">Starts with <code>sk_live_</code></div>
                        </div>
                    </div>
                </div>

                <!-- Webhook & Currency Section -->
                <div style="margin-bottom:24px">
                    <div style="font-size:0.75rem;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;display:flex;align-items:center;gap:6px">
                        <span>⚙️</span> Webhook &amp; Currency Settings
                    </div>

                    <div class="settings-form-grid-2">
                        <!-- Webhook Signing Secret -->
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:700;color:#334155">Stripe Webhook Secret</label>
                            <input type="text" name="stripe_webhook_secret" class="form-control" style="border-radius:10px;font-family:monospace;font-size:0.85rem"
                                   value="{{ old('stripe_webhook_secret', $settings['stripe_webhook_secret'] ?? '') }}" placeholder="whsec_...">
                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px">Optional. Used for verifying Stripe webhook signature events.</div>
                        </div>

                        <!-- Currency Selector -->
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:700;color:#334155">Transaction Currency</label>
                            <select name="stripe_currency" class="form-control" style="border-radius:10px">
                                <option value="MYR" {{ old('stripe_currency', $settings['stripe_currency'] ?? 'MYR') === 'MYR' ? 'selected' : '' }}>MYR — Malaysian Ringgit (RM)</option>
                                <option value="SGD" {{ old('stripe_currency', $settings['stripe_currency'] ?? '') === 'SGD' ? 'selected' : '' }}>SGD — Singapore Dollar ($)</option>
                                <option value="USD" {{ old('stripe_currency', $settings['stripe_currency'] ?? '') === 'USD' ? 'selected' : '' }}>USD — US Dollar ($)</option>
                                <option value="EUR" {{ old('stripe_currency', $settings['stripe_currency'] ?? '') === 'EUR' ? 'selected' : '' }}>EUR — Euro (€)</option>
                                <option value="GBP" {{ old('stripe_currency', $settings['stripe_currency'] ?? '') === 'GBP' ? 'selected' : '' }}>GBP — British Pound (£)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Actions Row -->
                <div style="display:flex;justify-content:space-between;align-items:center;padding-top:16px;border-top:1px solid #f1f5f9;flex-wrap:wrap;gap:12px">
                    <button type="reset" class="btn btn-secondary" style="padding:10px 20px;border-radius:10px;font-weight:600">
                        ↺ Reset
                    </button>

                    <div style="display:flex;gap:10px;align-items:center">
                        <button type="button" onclick="document.getElementById('formTestStripe').submit();" class="btn" style="padding:10px 20px;border-radius:10px;font-weight:700;background:#eff6ff;border:1.5px solid #bfdbfe;color:#2563eb">
                            🔍 Verify Connection
                        </button>
                        <button type="submit" class="btn btn-primary" style="background:#2563eb;border-color:#2563eb;padding:10px 28px;font-weight:700;font-size:0.95rem;border-radius:10px;box-shadow:0 2px 6px rgba(37,99,235,0.25);display:inline-flex;align-items:center;gap:8px">
                            <span>💾</span> Save Payment Settings
                        </button>
                    </div>
                </div>
            </form>

            <form id="formTestStripe" action="{{ route('admin.settings.testStripe') }}" method="POST" style="display:none">
                @csrf
            </form>
        </div>

        <!-- ================= TAB: MULTI-CURRENCY & EXCHANGE RATES ================= -->
        <div id="tab-currency" class="settings-pane card" style="display:none;background:white;border-radius:14px;border:1.5px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);overflow:hidden">
            @php
                $currencySvc = app(\App\Services\CurrencyService::class);
                $liveRates = $currencySvc->getRates();
                $isAutoConvert = $currencySvc->isAutoConvert();
                $lastRateUpdate = \Illuminate\Support\Facades\Cache::get('currency_rates_updated_at', 'Live cache active');
            @endphp

            <div style="padding:18px 24px;border-bottom:1.5px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                <div>
                    <h2 style="font-size:1.15rem;font-weight:800;color:#0f172a;margin:0;display:flex;align-items:center;gap:8px">
                        <span>💱</span> Multi-Currency &amp; Exchange Rates
                    </h2>
                    <p style="font-size:0.82rem;color:#64748b;margin:4px 0 0">Configure header currencies (RM, SGD, USD), automatic API conversion, and manual price overrides.</p>
                </div>
                <div style="display:flex;align-items:center;gap:8px">
                    <span style="font-size:0.75rem;font-weight:700;padding:5px 12px;border-radius:20px;background:{{ $isAutoConvert ? '#ecfdf5;color:#047857;border:1px solid #a7f3d0' : '#fffbeb;color:#b45309;border:1px solid #fde68a' }}">
                        {{ $isAutoConvert ? '⚡ Auto API Conversion ON' : '✍️ Manual Product Pricing ON' }}
                    </span>
                </div>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" style="padding:clamp(16px,3vw,24px)">
                @csrf
                <input type="hidden" name="tab" value="currency">

                <!-- Mode Switch Box -->
                <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:14px;padding:20px;margin-bottom:24px">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:20px;flex-wrap:wrap">
                        <div style="flex:1;min-width:280px">
                            <label for="currency_auto_convert" style="font-weight:800;color:#0f172a;font-size:1rem;display:flex;align-items:center;gap:8px;margin-bottom:6px;cursor:pointer">
                                <span>🔄</span> Automatic Currency Conversion (Free Live API)
                            </label>
                            <p style="font-size:0.85rem;color:#64748b;line-height:1.5;margin:0">
                                When <strong>Enabled</strong>, product prices in <strong>SGD</strong> and <strong>USD</strong> are automatically calculated in real-time from standard <strong>RM</strong> rates using the free exchange rates API (<code>open.er-api.com</code>).<br>
                                When <strong>Disabled</strong>, the storefront displays the <strong>individual manual prices</strong> configured per product in the catalogue (e.g. custom SGD/USD rates).
                            </p>
                        </div>
                        <label class="switch-control" style="transform:scale(1.15);margin-top:4px">
                            <input type="checkbox" name="currency_auto_convert" id="currency_auto_convert" value="1" {{ old('currency_auto_convert', $settings['currency_auto_convert'] ?? '1') == '1' ? 'checked' : '' }}>
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Live Exchange Rate Indicators -->
                <div style="margin-bottom:24px">
                    <div style="font-weight:700;color:#0f172a;font-size:0.95rem;margin-bottom:12px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
                        <span>📊 Current Exchange Rates (Base: 1.00 MYR / RM)</span>
                        <div style="display:flex;gap:8px;align-items:center">
                            <span style="font-size:0.75rem;color:#64748b;">Source: open.er-api.com (Free)</span>
                            <button type="button" id="btnSyncRates" onclick="syncCurrencyRatesNow()" class="btn btn-sm" style="background:#eff6ff;border:1px solid #bfdbfe;color:#2563eb;font-weight:700;padding:5px 14px;border-radius:8px;display:inline-flex;align-items:center;gap:6px;cursor:pointer">
                                <span id="btnSyncRatesIcon">↻</span> <span id="btnSyncRatesText">Refresh Rates Now</span>
                            </button>
                        </div>
                    </div>

                    <div id="currencySyncFeedback" style="display:none;margin-bottom:14px;padding:10px 14px;border-radius:10px;font-size:0.85rem;font-weight:600"></div>

                    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:16px">
                        <!-- MYR Base Card -->
                        <div style="background:#ffffff;border:1.5px solid #e2e8f0;border-radius:12px;padding:16px">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                                <span style="font-size:1.4rem">🇲🇾</span>
                                <div>
                                    <div style="font-weight:800;color:#0f172a;font-size:0.95rem">RM (MYR)</div>
                                    <div style="font-size:0.75rem;color:#64748b">Base Store Currency</div>
                                </div>
                            </div>
                            <div style="font-size:1.35rem;font-weight:900;color:#0f172a;font-family:monospace">
                                1.0000 <span style="font-size:0.8rem;color:#64748b">MYR</span>
                            </div>
                        </div>

                        <!-- SGD Card -->
                        <div style="background:#ffffff;border:1.5px solid #e2e8f0;border-radius:12px;padding:16px">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                                <span style="font-size:1.4rem">🇸🇬</span>
                                <div>
                                    <div style="font-weight:800;color:#0f172a;font-size:0.95rem">SGD (S$)</div>
                                    <div style="font-size:0.75rem;color:#64748b">Singapore Dollar</div>
                                </div>
                            </div>
                            <div id="cardRateSgd" style="font-size:1.35rem;font-weight:900;color:#0284c7;font-family:monospace">
                                {{ number_format($liveRates['SGD'] ?? 0.3117, 4) }} <span style="font-size:0.8rem;color:#64748b">SGD / RM</span>
                            </div>
                        </div>

                        <!-- USD Card -->
                        <div style="background:#ffffff;border:1.5px solid #e2e8f0;border-radius:12px;padding:16px">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                                <span style="font-size:1.4rem">🇺🇸</span>
                                <div>
                                    <div style="font-weight:800;color:#0f172a;font-size:0.95rem">USD ($)</div>
                                    <div style="font-size:0.75rem;color:#64748b">United States Dollar</div>
                                </div>
                            </div>
                            <div id="cardRateUsd" style="font-size:1.35rem;font-weight:900;color:#16a34a;font-family:monospace">
                                {{ number_format($liveRates['USD'] ?? 0.2453, 4) }} <span style="font-size:0.8rem;color:#64748b">USD / RM</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Manual Fallback Exchange Rates -->
                <div style="background:#ffffff;border:1.5px solid #e2e8f0;border-radius:14px;padding:20px;margin-bottom:24px">
                    <div style="font-weight:700;color:#0f172a;font-size:0.95rem;margin-bottom:6px">
                        ⚙️ Manual Fallback Exchange Rates
                    </div>
                    <p style="font-size:0.82rem;color:#64748b;margin:0 0 16px">
                        These rates are used if Auto Conversion is switched OFF (for products without custom manual prices), or as a safeguard if the external API is ever unreachable.
                    </p>

                    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:16px">
                        <div class="form-group mb-0">
                            <label class="form-label" style="font-weight:700;font-size:0.85rem">1 MYR in SGD (S$)</label>
                            <input type="number" step="0.0001" min="0" name="currency_manual_rate_sgd" id="inputManualRateSgd"
                                   class="form-control" value="{{ old('currency_manual_rate_sgd', $settings['currency_manual_rate_sgd'] ?? '0.3117') }}" required>
                            <div class="form-hint" style="font-size:0.75rem;color:#64748b;margin-top:4px">Standard market rate: ~0.31</div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-label" style="font-weight:700;font-size:0.85rem">1 MYR in USD ($)</label>
                            <input type="number" step="0.0001" min="0" name="currency_manual_rate_usd" id="inputManualRateUsd"
                                   class="form-control" value="{{ old('currency_manual_rate_usd', $settings['currency_manual_rate_usd'] ?? '0.2453') }}" required>
                            <div class="form-hint" style="font-size:0.75rem;color:#64748b;margin-top:4px">Standard market rate: ~0.24</div>
                        </div>
                    </div>
                </div>

                <!-- Guidance Box on Manual Product Prices -->
                <div style="background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:12px;padding:16px;margin-bottom:24px;display:flex;gap:14px;align-items:flex-start">
                    <span style="font-size:1.4rem;line-height:1">💡</span>
                    <div style="font-size:0.85rem;color:#1e40af;line-height:1.6">
                        <strong>Manual Per-Product Pricing:</strong><br>
                        When Auto Conversion is turned <strong>OFF</strong>, the storefront uses the manual SGD &amp; USD prices configured directly on each product's edit page (<code>/admin/products/{slug}/edit</code>).
                        If a specific product does not have manual SGD or USD prices entered, the system gracefully falls back to the manual rate configured above.
                    </div>
                </div>

                <!-- Submit Row -->
                <div style="display:flex;justify-content:flex-end;align-items:center;padding-top:16px;border-top:1px solid #f1f5f9;gap:12px">
                    <button type="submit" class="btn btn-primary" style="background:#2563eb;border-color:#2563eb;padding:10px 28px;font-weight:700;font-size:0.95rem;border-radius:10px;box-shadow:0 2px 6px rgba(37,99,235,0.25);display:inline-flex;align-items:center;gap:8px">
                        <span>💾</span> Save Currency Settings
                    </button>
                </div>
            </form>

            <form id="formSyncRates" action="{{ route('admin.settings.currency.sync') }}" method="POST" style="display:none">
                @csrf
            </form>
        </div>

        <!-- ================= TAB 3: MODULES SETTINGS ================= -->
        <div id="tab-modules" class="settings-pane card" style="display:none;background:white;border-radius:14px;border:1.5px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);overflow:hidden">

            {{-- Tab Header --}}
            <div style="padding:18px 24px;border-bottom:1.5px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                <div>
                    <h2 style="font-size:1.15rem;font-weight:800;color:#0f172a;margin:0;display:flex;align-items:center;gap:8px">
                        <span>🧩</span> Modules &amp; System Features
                    </h2>
                    <p style="font-size:0.82rem;color:#64748b;margin:4px 0 0">Enable or disable platform features. Changes take effect immediately after saving.</p>
                </div>
                <div id="modules-active-count" style="font-size:0.8rem;font-weight:700;color:#4f46e5;background:#eef2ff;padding:6px 14px;border-radius:20px;border:1px solid #c7d2fe;white-space:nowrap">
                    Loading...
                </div>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" style="padding:clamp(16px,3vw,24px)" id="modulesSettingsForm">
                @csrf
                <input type="hidden" name="tab" value="modules">

                @php
                    $modulesList = [
                        [
                            'key'         => 'module_walkin_flow',
                            'icon'        => '📱',
                            'label'       => 'Walk-in Customer Flow',
                            'badge'       => 'Retail',
                            'badge_color' => '#7c3aed',
                            'badge_bg'    => '#ede9fe',
                            'desc'        => 'Allows visitors at the physical showroom to scan QR code, access dedicated walk-in catalogue, and receive pickup tokens.',
                        ],
                        [
                            'key'         => 'module_wholesale_approval',
                            'icon'        => '🏢',
                            'label'       => 'Wholesale Registration Verification',
                            'badge'       => 'B2B',
                            'badge_color' => '#0369a1',
                            'badge_bg'    => '#e0f2fe',
                            'desc'        => 'Enforces admin review and approval before wholesale customers can log in and view tiered wholesale pricing.',
                        ],
                        [
                            'key'         => 'module_rfq_trading',
                            'icon'        => '📋',
                            'label'       => 'Trading Customer RFQ Only Mode',
                            'badge'       => 'Trading',
                            'badge_color' => '#b45309',
                            'badge_bg'    => '#fef3c7',
                            'desc'        => 'Enables Request For Quotation workflow without fixed prices for commercial high-volume container shipments.',
                        ],
                        [
                            'key'         => 'module_stock_tracking',
                            'icon'        => '📦',
                            'label'       => 'Live Inventory & Stock Warning Tracking',
                            'badge'       => 'Inventory',
                            'badge_color' => '#065f46',
                            'badge_bg'    => '#d1fae5',
                            'desc'        => 'Tracks inventory deductions upon checkout and flags low-stock warnings in product lists.',
                        ],
                        [
                            'key'         => 'module_online_payment',
                            'icon'        => '💳',
                            'label'       => 'Online Payment Gateways (Card & FPX)',
                            'badge'       => 'Payments',
                            'badge_color' => '#9f1239',
                            'badge_bg'    => '#ffe4e6',
                            'desc'        => 'Enables seamless digital checkout with simulated Stripe / FPX payment gateways.',
                        ],
                        [
                            'key'         => 'module_inquiry_emails',
                            'icon'        => '📨',
                            'label'       => 'Contact Inquiry Email Notifications',
                            'badge'       => 'Emails',
                            'badge_color' => '#374151',
                            'badge_bg'    => '#f3f4f6',
                            'desc'        => 'Dispatches alert emails to secondary and admin email when a customer submits the Contact form.',
                        ],
                    ];
                @endphp

                <div class="modules-grid">
                    @foreach($modulesList as $mod)
                    @php $isOn = ($settings[$mod['key']] ?? '1') == '1'; @endphp
                    <div class="module-card {{ $isOn ? 'module-card-on' : '' }}" id="module-card-{{ $mod['key'] }}">
                        {{-- Card Top Row: Icon + Labels --}}
                        <div style="display:flex;align-items:flex-start;gap:14px">
                            <div class="module-icon-wrap {{ $isOn ? 'module-icon-on' : '' }}" id="module-icon-{{ $mod['key'] }}">
                                <span style="font-size:1.5rem;line-height:1">{{ $mod['icon'] }}</span>
                            </div>
                            <div style="flex:1;min-width:0">
                                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:5px">
                                    <div class="module-title">{{ $mod['label'] }}</div>
                                    <span class="module-badge" style="color:{{ $mod['badge_color'] }};background:{{ $mod['badge_bg'] }}">
                                        {{ $mod['badge'] }}
                                    </span>
                                </div>
                                <div class="module-desc">{{ $mod['desc'] }}</div>
                            </div>
                        </div>

                        {{-- Card Bottom Row: Status label + Toggle --}}
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:14px;padding-top:12px;border-top:1px solid #f1f5f9">
                            <span class="module-status-label {{ $isOn ? 'module-status-on' : 'module-status-off' }}" id="module-status-{{ $mod['key'] }}">
                                {{ $isOn ? '✓ Active' : '✕ Disabled' }}
                            </span>
                            <label class="module-toggle-wrap" title="Toggle {{ $mod['label'] }}">
                                <input type="checkbox"
                                       name="{{ $mod['key'] }}"
                                       value="1"
                                       class="module-toggle-input"
                                       data-card="{{ $mod['key'] }}"
                                       {{ $isOn ? 'checked' : '' }}>
                                <span class="module-toggle-track">
                                    <span class="module-toggle-thumb"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Info note --}}
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:12px 16px;display:flex;gap:12px;align-items:flex-start;margin-top:4px;margin-bottom:20px">
                    <span style="font-size:1.1rem;flex-shrink:0">💡</span>
                    <div style="font-size:0.78rem;color:#475569;line-height:1.5">
                        Disabled modules are <strong>hidden from customers</strong> but retain all existing data. You can re-enable them at any time without data loss.
                    </div>
                </div>

                {{-- Save Row --}}
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                    <div id="modules-changed-hint" style="font-size:0.78rem;color:#f59e0b;font-weight:600;display:none;align-items:center;gap:6px">
                        <span>⚠️</span> You have unsaved changes
                    </div>
                    <div style="margin-left:auto">
                        <button type="submit" class="btn btn-primary settings-contact-save-btn"
                                style="background:#4f46e5;border-color:#4f46e5;padding:11px 32px;font-weight:700;font-size:0.95rem;border-radius:10px;box-shadow:0 2px 6px rgba(79,70,229,0.25);display:inline-flex;align-items:center;gap:8px">
                            <span>💾</span> Save Module Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>


        <!-- ================= TAB 4: WEBSITE TRACKING ================= -->
        <div id="tab-tracking" class="settings-pane card" style="display:none;background:white;border-radius:12px;border:1px solid var(--gray-200);box-shadow:0 1px 3px rgba(0,0,0,0.05);overflow:hidden">
            <div style="padding:16px 22px;border-bottom:1px solid var(--gray-200);background:#fafafa">
                <h2 style="font-size:1.05rem;font-weight:800;color:var(--gray-900);margin:0">Website Tracking & Pixels</h2>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" style="padding:22px">
                @csrf
                <input type="hidden" name="tab" value="tracking">

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">Google Analytics (GA4) Measurement ID</label>
                    <input type="text" name="tracking_ga4_id" class="form-control" value="{{ old('tracking_ga4_id', $settings['tracking_ga4_id'] ?? '') }}" placeholder="G-XXXXXXXXXX">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">Google Tag Manager (GTM) Container ID</label>
                    <input type="text" name="tracking_gtm_id" class="form-control" value="{{ old('tracking_gtm_id', $settings['tracking_gtm_id'] ?? '') }}" placeholder="GTM-XXXXXXX">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">Facebook (Meta) Pixel ID</label>
                    <input type="text" name="tracking_fb_pixel" class="form-control" value="{{ old('tracking_fb_pixel', $settings['tracking_fb_pixel'] ?? '') }}" placeholder="123456789012345">
                </div>

                <div class="form-group mb-6">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">TikTok Pixel ID</label>
                    <input type="text" name="tracking_tiktok_pixel" class="form-control" value="{{ old('tracking_tiktok_pixel', $settings['tracking_tiktok_pixel'] ?? '') }}" placeholder="CXXXXXXXXXXXXXXXXX">
                </div>

                <div style="display:flex;justify-content:flex-end">
                    <button type="submit" class="btn btn-primary" style="background:#5b5bf0;border-color:#5b5bf0;padding:10px 24px;font-weight:700">
                        Save Tracking Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- ================= TAB 5: SITE APPEARANCE ================= -->
        <div id="tab-appearance" class="settings-pane card" style="display:none;background:white;border-radius:14px;border:1.5px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);overflow:hidden">

            {{-- Tab Header --}}
            <div style="padding:18px 24px;border-bottom:1.5px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                <div>
                    <h2 style="font-size:1.15rem;font-weight:800;color:#0f172a;margin:0;display:flex;align-items:center;gap:8px">
                        <span>🎨</span> Site Appearance &amp; Branding
                    </h2>
                    <p style="font-size:0.82rem;color:#64748b;margin:4px 0 0">Configure your site logo, browser favicon, and storefront footer copyright text.</p>
                </div>
                <span style="font-size:0.75rem;font-weight:700;color:#059669;background:#ecfdf5;padding:4px 12px;border-radius:20px;border:1px solid #a7f3d0">
                    ● Synced to Storefront
                </span>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" style="padding:clamp(16px,3vw,24px)" id="appearanceSettingsForm">
                @csrf
                <input type="hidden" name="tab" value="appearance">

                {{-- Section: Brand Assets --}}
                <div style="margin-bottom:28px;padding-bottom:24px;border-bottom:1px solid #f1f5f9">
                    <div style="font-size:0.75rem;font-weight:800;color:#4f46e5;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:16px;display:flex;align-items:center;gap:6px">
                        <span>🖼️</span> Brand Assets
                    </div>

                    <div class="appearance-assets-grid">

                        {{-- Site Logo Card --}}
                        <div class="appearance-asset-card">
                            <div class="appearance-asset-header">
                                <div>
                                    <div style="font-weight:700;color:#0f172a;font-size:0.9rem">Site Logo</div>
                                    <div style="font-size:0.75rem;color:#64748b;margin-top:2px">Shown in navbar and footer</div>
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm appearance-gallery-btn" onclick="pickSiteLogo()">
                                    🖼️ Gallery
                                </button>
                            </div>

                            {{-- Logo Preview --}}
                            <div id="logoPreviewBox" class="appearance-preview-box">
                                @if(!empty($settings['site_logo']))
                                    <img id="logoPreviewImg" src="{{ asset('storage/'.$settings['site_logo']) }}" class="appearance-logo-img">
                                    <span id="logoPlaceholder" style="display:none" class="appearance-placeholder">🐟 Mika</span>
                                @else
                                    <span id="logoPlaceholder" class="appearance-placeholder">🐟 No Logo Set</span>
                                    <img id="logoPreviewImg" src="" class="appearance-logo-img" style="display:none">
                                @endif
                            </div>

                            {{-- Logo Status Badge --}}
                            <div id="logo-status-badge" class="appearance-file-badge {{ !empty($settings['site_logo']) ? 'appearance-file-badge-set' : 'appearance-file-badge-empty' }}">
                                @if(!empty($settings['site_logo']))
                                    ✓ Logo set — {{ basename($settings['site_logo']) }}
                                @else
                                    ○ No logo set — upload or pick from gallery
                                @endif
                            </div>

                            <input type="hidden" name="gallery_site_logo" id="gallerySiteLogoInput" value="{{ $settings['site_logo'] ?? '' }}">
                            <div style="margin-top:10px">
                                <label style="font-size:0.75rem;font-weight:700;color:#64748b;margin-bottom:5px;display:block">Or upload a file</label>
                                <input type="file" name="site_logo" class="form-control appearance-file-input" accept="image/*" onchange="previewUpload(this, 'logoPreviewImg', 'logoPlaceholder', 'logo-status-badge')">
                                <div class="appearance-hint">PNG, SVG, or WebP recommended. Transparent background works best.</div>
                            </div>
                        </div>

                        {{-- Site Favicon Card --}}
                        <div class="appearance-asset-card">
                            <div class="appearance-asset-header">
                                <div>
                                    <div style="font-weight:700;color:#0f172a;font-size:0.9rem">Browser Favicon</div>
                                    <div style="font-size:0.75rem;color:#64748b;margin-top:2px">Small icon in browser tabs</div>
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm appearance-gallery-btn" onclick="pickSiteFavicon()">
                                    🖼️ Gallery
                                </button>
                            </div>

                            {{-- Favicon Preview --}}
                            <div id="faviconPreviewBox" class="appearance-preview-box">
                                @if(!empty($settings['site_favicon']))
                                    <img id="faviconPreviewImg" src="{{ asset('storage/'.$settings['site_favicon']) }}" class="appearance-favicon-img">
                                    <span id="faviconPlaceholder" style="display:none" class="appearance-placeholder">🐟</span>
                                @else
                                    <span id="faviconPlaceholder" class="appearance-placeholder" style="font-size:2.5rem">🐟</span>
                                    <img id="faviconPreviewImg" src="" class="appearance-favicon-img" style="display:none">
                                @endif
                            </div>

                            {{-- Favicon Status Badge --}}
                            <div id="favicon-status-badge" class="appearance-file-badge {{ !empty($settings['site_favicon']) ? 'appearance-file-badge-set' : 'appearance-file-badge-empty' }}">
                                @if(!empty($settings['site_favicon']))
                                    ✓ Favicon set — {{ basename($settings['site_favicon']) }}
                                @else
                                    ○ No favicon set — upload or pick from gallery
                                @endif
                            </div>

                            <input type="hidden" name="gallery_site_favicon" id="gallerySiteFaviconInput" value="{{ $settings['site_favicon'] ?? '' }}">
                            <div style="margin-top:10px">
                                <label style="font-size:0.75rem;font-weight:700;color:#64748b;margin-bottom:5px;display:block">Or upload a file</label>
                                <input type="file" name="site_favicon" class="form-control appearance-file-input" accept="image/*" onchange="previewUpload(this, 'faviconPreviewImg', 'faviconPlaceholder', 'favicon-status-badge')">
                                <div class="appearance-hint">ICO or 32×32 / 64×64 PNG recommended for best browser compatibility.</div>
                            </div>
                        </div>

                    </div>{{-- /.appearance-assets-grid --}}
                </div>

                {{-- Section: Footer Copyright --}}
                <div style="margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid #f1f5f9">
                    <div style="font-size:0.75rem;font-weight:800;color:#4f46e5;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:16px;display:flex;align-items:center;gap:6px">
                        <span>©</span> Footer Copyright
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-weight:700;color:#1e293b;font-size:0.875rem;margin-bottom:6px">Copyright Text</label>
                        <input type="text" name="footer_copyright" id="input_footer_copyright" class="form-control settings-input" style="border-radius:10px;height:42px"
                               value="{{ old('footer_copyright', $settings['footer_copyright'] ?? '') }}"
                               placeholder="© {{ date('Y') }} Mika Import and Export SDN Bhd. All rights reserved.">
                        <div class="appearance-hint">Shown in the storefront footer. Use &amp;copy; for the © symbol or type it directly. Year is not auto-inserted.</div>
                    </div>

                    {{-- Live Footer Preview --}}
                    <div style="margin-top:14px;background:#0f172a;border-radius:10px;padding:16px 20px;display:flex;align-items:center;justify-content:center;gap:12px">
                        <span id="logoFooterPreview" style="font-size:1.1rem;font-weight:800;color:#ffffff;letter-spacing:-0.5px">🐟 Mika</span>
                        <span style="color:#475569;font-size:1rem">|</span>
                        <span id="preview-footer-copyright" style="font-size:0.82rem;color:#94a3b8;font-style:italic">
                            {{ $settings['footer_copyright'] ?? '© ' . date('Y') . ' Mika Import and Export SDN Bhd. All rights reserved.' }}
                        </span>
                    </div>
                    <div style="text-align:center;font-size:0.72rem;color:#94a3b8;margin-top:6px">👆 Live footer preview — updates as you type</div>
                </div>

                {{-- Save Row --}}
                <div style="display:flex;justify-content:flex-end">
                    <button type="submit" class="btn btn-primary settings-contact-save-btn"
                            style="background:#4f46e5;border-color:#4f46e5;padding:11px 32px;font-weight:700;font-size:0.95rem;border-radius:10px;box-shadow:0 2px 6px rgba(79,70,229,0.25);display:inline-flex;align-items:center;gap:8px">
                        <span>💾</span> Save Appearance
                    </button>
                </div>
            </form>
        </div>


        <!-- ================= TAB 6: RECAPTCHA SETTINGS ================= -->
        <div id="tab-recaptcha" class="settings-pane card" style="display:none;background:white;border-radius:12px;border:1px solid var(--gray-200);box-shadow:0 1px 3px rgba(0,0,0,0.05);overflow:hidden">
            <div style="padding:16px 22px;border-bottom:1px solid var(--gray-200);background:#fafafa">
                <h2 style="font-size:1.05rem;font-weight:800;color:var(--gray-900);margin:0">Google reCAPTCHA Protection</h2>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" style="padding:22px">
                @csrf
                <input type="hidden" name="tab" value="recaptcha">

                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
                    <div>
                        <div style="font-weight:700;color:#0f172a;font-size:0.95rem">Enable Google reCAPTCHA Protection</div>
                        <div style="font-size:0.8rem;color:#64748b;margin-top:2px">Protect Contact Us &amp; Registration forms against automated spam bots.</div>
                    </div>
                    <label class="switch-toggle" title="Toggle reCAPTCHA">
                        <input type="checkbox" name="recaptcha_enabled" value="1" {{ ($settings['recaptcha_enabled'] ?? '0') == '1' ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">reCAPTCHA Site Key</label>
                    <input type="text" name="recaptcha_site_key" class="form-control" value="{{ old('recaptcha_site_key', $settings['recaptcha_site_key'] ?? '') }}" placeholder="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">reCAPTCHA Secret Key</label>
                    <input type="password" name="recaptcha_secret_key" class="form-control" value="{{ old('recaptcha_secret_key', $settings['recaptcha_secret_key'] ?? '') }}" placeholder="••••••••••••••••••••••••••••••••••••••••">
                </div>

                <div class="form-group mb-6">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">Protected Form Actions</label>
                    <div style="display:flex;flex-direction:column;gap:8px;margin-top:6px">
                        <label style="display:flex;align-items:center;gap:8px;font-size:0.85rem;cursor:pointer">
                            <input type="checkbox" name="recaptcha_on_contact" value="1" {{ ($settings['recaptcha_on_contact'] ?? '1') == '1' ? 'checked' : '' }} style="accent-color:#4f46e5">
                            Protect Contact Us form from spam
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;font-size:0.85rem;cursor:pointer">
                            <input type="checkbox" name="recaptcha_on_register" value="1" {{ ($settings['recaptcha_on_register'] ?? '1') == '1' ? 'checked' : '' }} style="accent-color:#4f46e5">
                            Protect Wholesale Account registration from bots
                        </label>
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end">
                    <button type="submit" class="btn btn-primary" style="background:#5b5bf0;border-color:#5b5bf0;padding:10px 24px;font-weight:700">
                        Save reCAPTCHA Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- ================= TAB 7: SITE KEYS ================= -->
        <div id="tab-keys" class="settings-pane card" style="display:none;background:white;border-radius:12px;border:1px solid var(--gray-200);box-shadow:0 1px 3px rgba(0,0,0,0.05);overflow:hidden">
            <div style="padding:16px 22px;border-bottom:1px solid var(--gray-200);background:#fafafa">
                <h2 style="font-size:1.05rem;font-weight:800;color:var(--gray-900);margin:0">API & Payment Site Keys</h2>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" style="padding:22px">
                @csrf
                <input type="hidden" name="tab" value="keys">

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">Stripe / Payment Publishable Key</label>
                    <input type="text" name="stripe_key" class="form-control" value="{{ old('stripe_key', $settings['stripe_key'] ?? '') }}" placeholder="pk_test_...">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">Stripe Secret Key</label>
                    <input type="password" name="stripe_secret" class="form-control" value="{{ old('stripe_secret', $settings['stripe_secret'] ?? '') }}" placeholder="sk_test_...">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">WhatsApp Business API Key</label>
                    <input type="password" name="whatsapp_api_key" class="form-control" value="{{ old('whatsapp_api_key', $settings['whatsapp_api_key'] ?? '') }}" placeholder="••••••••••••">
                </div>

                <div class="form-group mb-6">
                    <label class="form-label" style="font-weight:700;color:var(--gray-800)">Webhook / Cloudflare Signing Secret</label>
                    <input type="password" name="webhook_signing_secret" class="form-control" value="{{ old('webhook_signing_secret', $settings['webhook_signing_secret'] ?? '') }}" placeholder="whsec_...">
                </div>

                <div style="display:flex;justify-content:flex-end">
                    <button type="submit" class="btn btn-primary" style="background:#5b5bf0;border-color:#5b5bf0;padding:10px 24px;font-weight:700">
                        Save Site Keys
                    </button>
                </div>
            </form>
        </div>

        <!-- ================= TAB: DATABASE BACKUP ================= -->
        <div id="tab-database" class="settings-pane card" style="display:none;background:white;border-radius:12px;border:1px solid var(--gray-200);box-shadow:0 1px 3px rgba(0,0,0,0.05);overflow:hidden">
            <div style="padding:16px 22px;border-bottom:1px solid var(--gray-200);background:#fafafa;display:flex;justify-content:space-between;align-items:center">
                <div>
                    <h2 style="font-size:1.05rem;font-weight:800;color:var(--gray-900);margin:0">💾 MySQL Database Backup &amp; Export</h2>
                    <p class="text-xs text-muted" style="margin:4px 0 0 0">Generate and download a full SQL snapshot of your database safely from the admin panel</p>
                </div>
                <span class="badge" style="background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;font-size:0.75rem;padding:5px 10px;border-radius:6px;font-weight:700">🔒 Admin Only</span>
            </div>

            <div style="padding:22px">
                <!-- Database Environment Summary -->
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:14px;margin-bottom:24px">
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px">
                        <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;letter-spacing:0.5px">Database Name</div>
                        <div style="font-size:1.1rem;font-weight:800;color:#0f172a;margin-top:4px">{{ config('database.connections.mysql.database', 'oceanfresh') }}</div>
                    </div>
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px">
                        <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;letter-spacing:0.5px">Host &amp; Port</div>
                        <div style="font-size:1.1rem;font-weight:800;color:#0f172a;margin-top:4px">{{ config('database.connections.mysql.host', '127.0.0.1') }}:{{ config('database.connections.mysql.port', '3306') }}</div>
                    </div>
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px">
                        <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;letter-spacing:0.5px">Default Charset</div>
                        <div style="font-size:1.1rem;font-weight:800;color:#0f172a;margin-top:4px">utf8mb4 (Unicode)</div>
                    </div>
                </div>

                <!-- Action Cards: Export & Import Grid -->
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(360px, 1fr));gap:20px;margin-bottom:24px">
                    <!-- Export Card -->
                    <div style="border:1.5px solid #e0e7ff;background:#f5f7ff;border-radius:12px;padding:22px;display:flex;flex-direction:column;justify-content:space-between">
                        <div>
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                                <span style="font-size:1.5rem">📥</span>
                                <h3 style="font-size:1.05rem;font-weight:800;color:#1e3a8a;margin:0">Export Database (.sql)</h3>
                            </div>
                            <p style="font-size:0.85rem;color:#475569;line-height:1.6;margin:0 0 16px 0">
                                Generates an instantaneous, complete SQL dump of all tables (products, categories, users, orders, RFQs, policies, translations, settings) and downloads it directly to your device.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.database.download', ['filename' => 'mst_mysql_backup_' . date('Y-m-d_His') . '.sql']) }}" download="mst_mysql_backup_{{ date('Y-m-d_His') }}.sql" class="btn btn-primary" style="background:#2563eb;border-color:#2563eb;padding:12px 22px;font-size:0.92rem;font-weight:700;box-shadow:0 4px 12px rgba(37,99,235,0.25);border-radius:8px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;width:100%;justify-content:center">
                                <span>📥</span>
                                <span>Download SQL Snapshot</span>
                            </a>
                        </div>
                    </div>

                    <!-- Import Card -->
                    <div style="border:1.5px solid #fed7aa;background:#fffbeb;border-radius:12px;padding:22px;display:flex;flex-direction:column;justify-content:space-between">
                        <form id="dbImportForm" action="{{ route('admin.database.import') }}" method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;height:100%;justify-content:space-between">
                            @csrf
                            <div>
                                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                                    <span style="font-size:1.5rem">📤</span>
                                    <h3 style="font-size:1.05rem;font-weight:800;color:#9a3412;margin:0">Import Database (.sql / .gz)</h3>
                                </div>
                                <p style="font-size:0.85rem;color:#78350f;line-height:1.6;margin:0 0 14px 0">
                                    Upload and execute a <code style="background:#fef3c7;padding:2px 6px;border-radius:4px;font-weight:700">.sql</code> or <code style="background:#fef3c7;padding:2px 6px;border-radius:4px;font-weight:700">.sql.gz</code> dump directly into the active database.
                                </p>

                                <div style="margin-bottom:14px">
                                    <label style="display:block;border:2px dashed #f59e0b;background:#ffffff;border-radius:10px;padding:14px;text-align:center;cursor:pointer;transition:all 0.2s" ondragover="event.preventDefault();this.style.background='#fef3c7'" ondragleave="this.style.background='#ffffff'" ondrop="handleSqlFileDrop(event)">
                                        <input type="file" name="sql_file" id="sqlFileInput" accept=".sql,.gz,.txt" required style="display:none" onchange="handleSqlFileSelect(this)">
                                        <div style="font-size:1.3rem;margin-bottom:4px">📁</div>
                                        <div id="sqlFilePrompt" style="font-size:0.84rem;font-weight:700;color:#9a3412">Click or drag &amp; drop .sql file here</div>
                                        <div id="sqlFileInfo" style="display:none;font-size:0.8rem;color:#16a34a;font-weight:700;margin-top:4px"></div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <button type="button" onclick="promptImportConfirm()" class="btn" style="background:#ea580c;border-color:#ea580c;color:#ffffff;padding:12px 22px;font-size:0.92rem;font-weight:700;box-shadow:0 4px 12px rgba(234,88,12,0.25);border-radius:8px;width:100%;display:inline-flex;align-items:center;justify-content:center;gap:8px;cursor:pointer">
                                    <span>🚀</span>
                                    <span>Upload &amp; Import Database</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Database Maintenance & Migration Tools Card -->
                <div style="border:1.5px solid #e2e8f0;background:#ffffff;border-radius:12px;padding:20px;margin-bottom:24px">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:14px">
                        <div>
                            <h3 style="font-size:0.95rem;font-weight:800;color:#0f172a;margin:0 0 4px 0">⚡ Database Maintenance &amp; Migrations</h3>
                            <p style="font-size:0.82rem;color:#64748b;margin:0">
                                Run pending Laravel migrations or reseed application datasets without terminal access.
                            </p>
                        </div>
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:12px">
                        <!-- Run Migrations Button -->
                        <form id="formRunMigrations" action="{{ route('admin.database.migrate') }}" method="POST" style="margin:0">
                            @csrf
                            <button type="button" onclick="promptMigrateConfirm()" class="btn btn-secondary" style="font-size:0.85rem;font-weight:700;padding:9px 16px;border-radius:8px;display:inline-flex;align-items:center;gap:6px;border-color:#cbd5e1;color:#1e293b;background:#f8fafc">
                                <span>🛠️</span>
                                <span>Run Pending Migrations (<code style="font-size:0.8rem">migrate --force</code>)</span>
                            </button>
                        </form>

                        <!-- Reseed Translations -->
                        <form action="{{ route('admin.database.seed') }}" method="POST" style="margin:0">
                            @csrf
                            <input type="hidden" name="seeder_class" value="Database\Seeders\TranslationSeeder">
                            <button type="submit" class="btn btn-secondary" style="font-size:0.85rem;font-weight:600;padding:9px 16px;border-radius:8px;display:inline-flex;align-items:center;gap:6px;border-color:#cbd5e1;color:#334155;background:#ffffff" onclick="return confirm('Reseed multilingual translation dictionary into database?')">
                                <span>🌐</span>
                                <span>Reseed Translations</span>
                            </button>
                        </form>

                        <!-- Reseed Catalogue -->
                        <form action="{{ route('admin.database.seed') }}" method="POST" style="margin:0">
                            @csrf
                            <input type="hidden" name="seeder_class" value="Database\Seeders\MultilingualCatalogueSeeder">
                            <button type="submit" class="btn btn-secondary" style="font-size:0.85rem;font-weight:600;padding:9px 16px;border-radius:8px;display:inline-flex;align-items:center;gap:6px;border-color:#cbd5e1;color:#334155;background:#ffffff" onclick="return confirm('Reseed multilingual product titles and descriptions?')">
                                <span>🐟</span>
                                <span>Reseed Product Translations</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Existing Backups Info -->
                @php
                    $backupFiles = array_merge(glob(storage_path('app/backups/*.sql')), glob(storage_path('app/backups/*.mysql')), glob(storage_path('app/backups/*.gz')));
                    if (!empty($backupFiles)) {
                        usort($backupFiles, fn($a, $b) => filemtime($b) <=> filemtime($a));
                    }
                @endphp
                @if(!empty($backupFiles))
                <div id="databaseBackupsCard" style="margin-top:16px">
                    <div style="font-size:0.85rem;font-weight:700;color:#334155;margin-bottom:10px">Stored Backup Archives in Server Storage</div>
                    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:8px;overflow:hidden">
                        <table class="table" style="margin:0;width:100%;font-size:0.82rem">
                            <thead>
                                <tr style="background:#f8fafc">
                                    <th style="padding:10px 14px">Archive File</th>
                                    <th style="padding:10px 14px">File Size</th>
                                    <th style="padding:10px 14px">Created Date</th>
                                    <th style="padding:10px 14px;text-align:right">Action</th>
                                </tr>
                            </thead>
                            <tbody id="databaseBackupsTbody">
                                @foreach(array_slice($backupFiles, 0, 20) as $bFile)
                                <tr id="backup-row-{{ md5(basename($bFile)) }}" style="transition: all 0.3s ease;">
                                    <td style="padding:10px 14px;font-weight:600;color:#0f172a">
                                        📄 {{ basename($bFile) }}
                                    </td>
                                    <td style="padding:10px 14px;color:#64748b">
                                        {{ number_format(filesize($bFile) / 1024, 1) }} KB
                                    </td>
                                    <td style="padding:10px 14px;color:#64748b">
                                        {{ date('d M Y, h:i A', filemtime($bFile)) }}
                                    </td>
                                    <td style="padding:10px 14px;text-align:right">
                                        <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px">
                                            <button type="button" class="btn btn-sm" onclick="openRestoreBackupModal('{{ basename($bFile) }}', '{{ route('admin.database.restore', ['filename' => basename($bFile)]) }}', '{{ number_format(filesize($bFile) / 1024, 1) }} KB')" style="background:#fff7ed;border:1.5px solid #fdba74;color:#c2410c;font-size:0.75rem;padding:4px 10px;border-radius:6px;font-weight:700;transition:all 0.15s ease;cursor:pointer">
                                                🔄 Restore
                                            </button>
                                            <button type="button" class="btn btn-sm" onclick="openDeleteBackupModal('{{ basename($bFile) }}', '{{ route('admin.database.destroy', ['filename' => basename($bFile)]) }}', 'backup-row-{{ md5(basename($bFile)) }}', '{{ number_format(filesize($bFile) / 1024, 1) }} KB')" style="background:#ffffff;border:1.5px solid #ef4444;color:#ef4444;font-size:0.75rem;padding:4px 10px;border-radius:6px;font-weight:700;transition:all 0.15s ease;cursor:pointer" onmouseover="this.style.background='#ef4444';this.style.color='#ffffff'" onmouseout="this.style.background='#ffffff';this.style.color='#ef4444'">
                                                Delete
                                            </button>
                                            <a href="{{ route('admin.database.download', ['filename' => basename($bFile)]) }}" download="{{ preg_replace('/\.(sql|mysql|gz)$/i', '', basename($bFile)) }}.sql" class="btn btn-secondary btn-sm" style="font-size:0.75rem;padding:4px 10px;border-radius:6px;font-weight:600">
                                                Download
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div id="noBackupsMessage" style="margin-top:16px;padding:24px;text-align:center;color:#64748b;font-size:0.875rem;background:#ffffff;border:1px solid #e2e8f0;border-radius:8px">
                    No backup archives currently stored on server.
                </div>
                @endif

                <!-- Security & Notice Box -->
                <div style="margin-top:24px;padding:14px 16px;background:#fefce8;border:1px solid #fef08a;border-radius:8px;font-size:0.8rem;color:#854d0e;line-height:1.5">
                    <strong>Security Notice:</strong> Database operations directly modify your live database tables. Always ensure you have downloaded a fresh SQL snapshot before performing imports or restores.
                </div>
            </div>
        </div>

        <!-- ================= TAB: RECAPTCHA SETTINGS ================= -->
        <div id="tab-recaptcha" class="settings-pane card" style="display:none;background:white;border-radius:14px;border:1.5px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);overflow:hidden">
            <div style="padding:18px 24px;border-bottom:1.5px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                <div>
                    <h2 style="font-size:1.15rem;font-weight:800;color:#0f172a;margin:0;display:flex;align-items:center;gap:8px">
                        <span>🛡️</span> Google reCAPTCHA Settings
                    </h2>
                    <p style="font-size:0.82rem;color:#64748b;margin:4px 0 0">
                        Protect contact inquiries, user login, and account registrations against automated bots and spam
                    </p>
                </div>
                @php
                    $isRecaptchaActive = ($settings['recaptcha_enabled'] ?? '0') === '1' && !empty($settings['recaptcha_site_key']) && !empty($settings['recaptcha_secret_key']);
                @endphp
                <div>
                    @if($isRecaptchaActive)
                        <span style="display:inline-flex;align-items:center;gap:6px;background:#dcfce7;color:#15803d;border:1px solid #86efac;padding:5px 12px;border-radius:999px;font-size:0.75rem;font-weight:700">
                            <span style="width:7px;height:7px;border-radius:50%;background:#16a34a"></span>
                            reCAPTCHA Active
                        </span>
                    @elseif(($settings['recaptcha_enabled'] ?? '0') === '1')
                        <span style="display:inline-flex;align-items:center;gap:6px;background:#fef9c3;color:#854d0e;border:1px solid #fde047;padding:5px 12px;border-radius:999px;font-size:0.75rem;font-weight:700">
                            <span>⚠️</span> Keys Incomplete
                        </span>
                    @else
                        <span style="display:inline-flex;align-items:center;gap:6px;background:#f1f5f9;color:#64748b;border:1px solid #cbd5e1;padding:5px 12px;border-radius:999px;font-size:0.75rem;font-weight:700">
                            <span>⚪</span> Disabled
                        </span>
                    @endif
                </div>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" style="padding:24px">
                @csrf
                <input type="hidden" name="tab" value="recaptcha">

                <!-- 1. Master Toggle Card -->
                <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;padding:18px 20px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px">
                    <div>
                        <div style="font-weight:800;font-size:0.95rem;color:#0f172a;display:flex;align-items:center;gap:8px">
                            <span>🤖</span> Enable Google reCAPTCHA Protection
                        </div>
                        <div style="font-size:0.8rem;color:#64748b;margin-top:3px">
                            When enabled, verification is required on all activated forms below before submission.
                        </div>
                    </div>
                    <label class="switch-toggle" style="margin:0">
                        <input type="checkbox" name="recaptcha_enabled" value="1" {{ ($settings['recaptcha_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>

                <!-- 2. Google API Keys Section -->
                <div style="margin-bottom:24px">
                    <div style="font-size:0.88rem;font-weight:800;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:6px">
                        <span>🔑</span> API Credentials (Google reCAPTCHA v2 Checkbox)
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px" class="settings-form-grid-2">
                        <!-- Site Key -->
                        <div class="form-group mb-0">
                            <label class="form-label" style="font-weight:700;color:#334155;font-size:0.85rem">
                                Google Site Key (Public) <span class="required" style="color:#ef4444">*</span>
                            </label>
                            <input type="text" name="recaptcha_site_key" class="form-control"
                                   value="{{ old('recaptcha_site_key', $settings['recaptcha_site_key'] ?? '') }}"
                                   placeholder="e.g. 6LdXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
                                   style="font-family:monospace;font-size:0.85rem;background:#ffffff">
                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px">
                                The public key used to render the widget on client browsers.
                            </div>
                        </div>

                        <!-- Secret Key -->
                        <div class="form-group mb-0">
                            <label class="form-label" style="font-weight:700;color:#334155;font-size:0.85rem">
                                Google Secret Key (Private) <span class="required" style="color:#ef4444">*</span>
                            </label>
                            <div style="position:relative">
                                <input type="password" name="recaptcha_secret_key" id="recaptcha_secret_key" class="form-control"
                                       value="{{ old('recaptcha_secret_key', $settings['recaptcha_secret_key'] ?? '') }}"
                                       placeholder="e.g. 6LdXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
                                       style="font-family:monospace;font-size:0.85rem;background:#ffffff;padding-right:40px">
                                <button type="button" onclick="togglePasswordVisibility('recaptcha_secret_key')"
                                        style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:transparent;border:none;color:#64748b;cursor:pointer;padding:4px 8px;font-size:0.9rem" title="Show/Hide Key">
                                    👁
                                </button>
                            </div>
                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px">
                                Server-side verification secret. Kept secure and never sent to client browsers.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Form Placement Toggles Section -->
                <div style="margin-bottom:24px">
                    <div style="font-size:0.88rem;font-weight:800;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:6px">
                        <span>🎯</span> Form Placement &amp; Individual Activation
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:14px" class="settings-form-grid-3">

                        <!-- Placement 1: Contact Us -->
                        <div style="border:1.5px solid #e2e8f0;border-radius:12px;padding:16px;background:#ffffff;display:flex;flex-direction:column;justify-content:space-between;gap:12px">
                            <div>
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                                    <div style="font-size:1.4rem">📍</div>
                                    <label class="switch-toggle" style="margin:0">
                                        <input type="checkbox" name="recaptcha_on_contact" value="1" {{ ($settings['recaptcha_on_contact'] ?? '1') === '1' ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <div style="font-weight:700;font-size:0.9rem;color:#0f172a">Contact &amp; RFQ Form</div>
                                <div style="font-size:0.78rem;color:#64748b;margin-top:3px;line-height:1.4">
                                    Active on <code style="font-size:0.72rem;background:#f1f5f9;padding:1px 4px;border-radius:4px">/en/contact</code> sourcing RFQ inquiries.
                                </div>
                            </div>
                        </div>

                        <!-- Placement 2: Login -->
                        <div style="border:1.5px solid #e2e8f0;border-radius:12px;padding:16px;background:#ffffff;display:flex;flex-direction:column;justify-content:space-between;gap:12px">
                            <div>
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                                    <div style="font-size:1.4rem">🔐</div>
                                    <label class="switch-toggle" style="margin:0">
                                        <input type="checkbox" name="recaptcha_on_login" value="1" {{ ($settings['recaptcha_on_login'] ?? '1') === '1' ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <div style="font-weight:700;font-size:0.9rem;color:#0f172a">Customer &amp; Admin Login</div>
                                <div style="font-size:0.78rem;color:#64748b;margin-top:3px;line-height:1.4">
                                    Active on <code style="font-size:0.72rem;background:#f1f5f9;padding:1px 4px;border-radius:4px">/login</code> to protect against brute-force attacks.
                                </div>
                            </div>
                        </div>

                        <!-- Placement 3: Registration -->
                        <div style="border:1.5px solid #e2e8f0;border-radius:12px;padding:16px;background:#ffffff;display:flex;flex-direction:column;justify-content:space-between;gap:12px">
                            <div>
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                                    <div style="font-size:1.4rem">📝</div>
                                    <label class="switch-toggle" style="margin:0">
                                        <input type="checkbox" name="recaptcha_on_register" value="1" {{ ($settings['recaptcha_on_register'] ?? '1') === '1' ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <div style="font-weight:700;font-size:0.9rem;color:#0f172a">Customer Registration</div>
                                <div style="font-size:0.78rem;color:#64748b;margin-top:3px;line-height:1.4">
                                    Active on <code style="font-size:0.72rem;background:#f1f5f9;padding:1px 4px;border-radius:4px">/register</code> to block fake bot signups.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- 4. Setup Guidance & Instructions Card -->
                <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:18px 20px;margin-bottom:24px">
                    <div style="font-weight:700;font-size:0.86rem;color:#1e40af;margin-bottom:6px;display:flex;align-items:center;gap:6px">
                        <span>💡</span> How to get your Google reCAPTCHA Keys:
                    </div>
                    <ol style="margin:0;padding-left:20px;font-size:0.8rem;color:#1e3a8a;line-height:1.6">
                        <li>Visit the <a href="https://www.google.com/recaptcha/admin" target="_blank" style="color:#2563eb;font-weight:700;text-decoration:underline">Google reCAPTCHA Admin Console ↗</a> and sign in.</li>
                        <li>Register a new site, choose <strong>reCAPTCHA v2 ("I'm not a robot" Checkbox)</strong>.</li>
                        <li>Add your authorized domains: <code style="background:#dbeafe;padding:1px 4px;border-radius:3px">127.0.0.1</code>, <code style="background:#dbeafe;padding:1px 4px;border-radius:3px">localhost</code>, and your production domain (e.g. <code style="background:#dbeafe;padding:1px 4px;border-radius:3px">mst.my</code>).</li>
                        <li>Copy the generated <strong>Site Key</strong> and <strong>Secret Key</strong> into the fields above and click <strong>Save reCAPTCHA Settings</strong>.</li>
                    </ol>
                </div>

                <!-- Action Button -->
                <div style="display:flex;justify-content:flex-end">
                    <button type="submit" class="btn btn-primary" style="background:#1d4ed8;border-color:#1d4ed8;padding:10px 24px;font-weight:700;font-size:0.9rem;display:inline-flex;align-items:center;gap:8px">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>Save reCAPTCHA Settings</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- ================= TAB: ORDER SETTINGS ================= -->
        <div id="tab-order" class="settings-pane card" style="display:none;background:white;border-radius:14px;border:1.5px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);overflow:hidden">
            <div style="padding:18px 24px;border-bottom:1.5px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                <div>
                    <h2 style="font-size:1.15rem;font-weight:800;color:#0f172a;margin:0;display:flex;align-items:center;gap:8px">
                        <span>🛒</span> Order Settings
                    </h2>
                    <p style="font-size:0.82rem;color:#64748b;margin:4px 0 0">
                        Configure minimum order amounts per customer type. When enabled, customers cannot place orders below the set threshold.
                    </p>
                </div>
                <span id="orderMinBadge" style="font-size:0.75rem;font-weight:700;padding:4px 12px;border-radius:20px;border:1px solid;
                    {{ ($settings['order_minimum_enabled'] ?? '0') === '1' ? 'color:#059669;background:#ecfdf5;border-color:#a7f3d0' : 'color:#64748b;background:#f1f5f9;border-color:#e2e8f0' }}">
                    {{ ($settings['order_minimum_enabled'] ?? '0') === '1' ? '● Minimums Active' : '○ Minimums Disabled' }}
                </span>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" style="padding:clamp(16px, 3vw, 24px)" id="orderSettingsForm">
                @csrf
                <input type="hidden" name="tab" value="order">

                {{-- Enable / Disable Toggle --}}
                <div style="margin-bottom:28px;padding:20px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:14px">
                    <div style="font-size:0.75rem;font-weight:800;color:#4f46e5;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;display:flex;align-items:center;gap:6px">
                        <span>⚙️</span> Minimum Order Enforcement
                    </div>
                    <div style="display:flex;gap:20px;flex-wrap:wrap">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:14px 20px;border-radius:10px;border:2px solid;
                            {{ ($settings['order_minimum_enabled'] ?? '0') === '1' ? 'border-color:#4f46e5;background:#eef2ff' : 'border-color:#e2e8f0;background:white' }}
                            font-weight:700;font-size:0.9rem;flex:1;min-width:200px;transition:all 0.15s" id="labelMinEnabled">
                            <input type="radio" name="order_minimum_enabled" value="1"
                                {{ ($settings['order_minimum_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                                onchange="handleOrderMinToggle(this)"
                                style="width:18px;height:18px;accent-color:#4f46e5">
                            <span>
                                <span style="display:block;color:#3730a3">✅ Enabled</span>
                                <span style="font-weight:400;font-size:0.78rem;color:#6366f1">Minimum order amounts are enforced at checkout</span>
                            </span>
                        </label>
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:14px 20px;border-radius:10px;border:2px solid;
                            {{ ($settings['order_minimum_enabled'] ?? '0') !== '1' ? 'border-color:#4f46e5;background:#eef2ff' : 'border-color:#e2e8f0;background:white' }}
                            font-weight:700;font-size:0.9rem;flex:1;min-width:200px;transition:all 0.15s" id="labelMinDisabled">
                            <input type="radio" name="order_minimum_enabled" value="0"
                                {{ ($settings['order_minimum_enabled'] ?? '0') !== '1' ? 'checked' : '' }}
                                onchange="handleOrderMinToggle(this)"
                                style="width:18px;height:18px;accent-color:#64748b">
                            <span>
                                <span style="display:block;color:#374151">🚫 Disabled</span>
                                <span style="font-weight:400;font-size:0.78rem;color:#6b7280">No minimum enforced — all order amounts accepted</span>
                            </span>
                        </label>
                    </div>
                </div>

                {{-- Minimum Amounts Per Customer Type --}}
                <div id="orderMinFields" style="{{ ($settings['order_minimum_enabled'] ?? '0') !== '1' ? 'opacity:0.5;pointer-events:none;' : '' }}transition:opacity 0.2s">
                    <div style="font-size:0.75rem;font-weight:800;color:#4f46e5;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:16px;display:flex;align-items:center;gap:6px">
                        <span>💰</span> Minimum Order Amount by Customer Type (RM)
                    </div>

                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px">

                        {{-- Retail --}}
                        <div style="background:white;border:1.5px solid #e2e8f0;border-radius:14px;padding:20px;position:relative;overflow:hidden">
                            <div style="position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#10b981,#34d399)"></div>
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px">
                                <div style="width:40px;height:40px;border-radius:10px;background:#d1fae5;display:flex;align-items:center;justify-content:center;font-size:1.3rem">🛍️</div>
                                <div>
                                    <div style="font-weight:800;font-size:0.95rem;color:#0f172a">Retail Customers</div>
                                    <div style="font-size:0.75rem;color:#64748b">Walk-in & online retail orders</div>
                                </div>
                            </div>
                            <label style="font-size:0.78rem;font-weight:700;color:#374151;display:block;margin-bottom:6px">Minimum Order Amount</label>
                            <div style="position:relative">
                                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-weight:700;color:#10b981;font-size:0.9rem">RM</span>
                                <input type="number" name="order_minimum_retail" id="order_minimum_retail"
                                    min="0" step="0.01" placeholder="0.00"
                                    value="{{ old('order_minimum_retail', $settings['order_minimum_retail'] ?? '0') }}"
                                    class="form-control"
                                    style="padding-left:42px;border-radius:10px;height:44px;font-size:1rem;font-weight:700;border-color:#d1fae5">
                            </div>
                            <div style="font-size:0.72rem;color:#6b7280;margin-top:8px">Set to 0 to disable minimum for this group</div>
                        </div>

                        {{-- Wholesale --}}
                        <div style="background:white;border:1.5px solid #e2e8f0;border-radius:14px;padding:20px;position:relative;overflow:hidden">
                            <div style="position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#3b82f6,#60a5fa)"></div>
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px">
                                <div style="width:40px;height:40px;border-radius:10px;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-size:1.3rem">🏢</div>
                                <div>
                                    <div style="font-weight:800;font-size:0.95rem;color:#0f172a">Wholesale Customers</div>
                                    <div style="font-size:0.75rem;color:#64748b">Approved B2B wholesale buyers</div>
                                </div>
                            </div>
                            <label style="font-size:0.78rem;font-weight:700;color:#374151;display:block;margin-bottom:6px">Minimum Order Amount</label>
                            <div style="position:relative">
                                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-weight:700;color:#3b82f6;font-size:0.9rem">RM</span>
                                <input type="number" name="order_minimum_wholesale" id="order_minimum_wholesale"
                                    min="0" step="0.01" placeholder="0.00"
                                    value="{{ old('order_minimum_wholesale', $settings['order_minimum_wholesale'] ?? '0') }}"
                                    class="form-control"
                                    style="padding-left:42px;border-radius:10px;height:44px;font-size:1rem;font-weight:700;border-color:#dbeafe">
                            </div>
                            <div style="font-size:0.72rem;color:#6b7280;margin-top:8px">Set to 0 to disable minimum for this group</div>
                        </div>

                        {{-- Trading --}}
                        <div style="background:white;border:1.5px solid #e2e8f0;border-radius:14px;padding:20px;position:relative;overflow:hidden">
                            <div style="position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#8b5cf6,#a78bfa)"></div>
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px">
                                <div style="width:40px;height:40px;border-radius:10px;background:#ede9fe;display:flex;align-items:center;justify-content:center;font-size:1.3rem">🌐</div>
                                <div>
                                    <div style="font-weight:800;font-size:0.95rem;color:#0f172a">Trading / Distribution</div>
                                    <div style="font-size:0.75rem;color:#64748b">Import, export & distribution partners</div>
                                </div>
                            </div>
                            <label style="font-size:0.78rem;font-weight:700;color:#374151;display:block;margin-bottom:6px">Minimum Order Amount</label>
                            <div style="position:relative">
                                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-weight:700;color:#8b5cf6;font-size:0.9rem">RM</span>
                                <input type="number" name="order_minimum_trading" id="order_minimum_trading"
                                    min="0" step="0.01" placeholder="0.00"
                                    value="{{ old('order_minimum_trading', $settings['order_minimum_trading'] ?? '0') }}"
                                    class="form-control"
                                    style="padding-left:42px;border-radius:10px;height:44px;font-size:1rem;font-weight:700;border-color:#ede9fe">
                            </div>
                            <div style="font-size:0.72rem;color:#6b7280;margin-top:8px">Set to 0 to disable minimum for this group</div>
                        </div>

                    </div>

                    {{-- Info box --}}
                    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:16px 20px;margin-top:20px">
                        <div style="font-weight:700;font-size:0.86rem;color:#1e40af;margin-bottom:6px;display:flex;align-items:center;gap:6px">
                            <span>💡</span> How wholesale/trading minimums work
                        </div>
                        <ul style="margin:0;padding-left:18px;font-size:0.8rem;color:#1e3a8a;line-height:1.7">
                            <li>Minimums for wholesale and trading partners are enforced at checkout when enabled.</li>
                            <li>A warning banner also appears on the cart page showing how much more is needed.</li>
                            <li>Setting a value of <strong>RM 0.00</strong> disables the minimum for that specific customer group.</li>
                        </ul>
                    </div>
                </div>

                {{-- ================= B2C DELIVERY & TRANSPORTATION RULES SECTION ================= --}}
                <div style="margin-top:32px;padding-top:28px;border-top:2px dashed #e2e8f0">
                    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:16px">
                        <div>
                            <div style="font-size:0.75rem;font-weight:800;color:#0284c7;text-transform:uppercase;letter-spacing:0.8px;display:flex;align-items:center;gap:6px">
                                <span>🚚</span> B2C Delivery & Transportation Rate Rules
                            </div>
                            <h3 style="font-size:1.05rem;font-weight:800;color:#0f172a;margin:4px 0 0">
                                Standard Delivery Threshold & Area Transportation Rates
                            </h3>
                        </div>
                        <span style="font-size:0.75rem;font-weight:700;padding:4px 12px;border-radius:20px;border:1px solid #bae6fd;color:#0369a1;background:#f0f9ff">
                            ● Active B2C Delivery System
                        </span>
                    </div>

                    <p style="font-size:0.82rem;color:#64748b;margin:0 0 20px;line-height:1.5">
                        Orders at or above the threshold qualify for the standard local delivery arrangement (Free delivery). 
                        Orders below the threshold are <strong>never blocked</strong>; instead, an area transportation charge is automatically calculated based on the customer's delivery location / zone. 
                        <strong>Self-collection and walk-in orders are always exempt from thresholds and delivery charges.</strong>
                    </p>

                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:20px">
                        {{-- Threshold --}}
                        <div style="background:white;border:1.5px solid #e2e8f0;border-radius:14px;padding:20px;position:relative;overflow:hidden">
                            <div style="position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#0284c7,#38bdf8)"></div>
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
                                <div style="width:38px;height:38px;border-radius:10px;background:#e0f2fe;display:flex;align-items:center;justify-content:center;font-size:1.2rem">📦</div>
                                <div>
                                    <div style="font-weight:800;font-size:0.9rem;color:#0f172a">B2C Delivery Threshold</div>
                                    <div style="font-size:0.72rem;color:#64748b">Free / standard local delivery</div>
                                </div>
                            </div>
                            <label style="font-size:0.78rem;font-weight:700;color:#374151;display:block;margin-bottom:6px">Threshold Amount</label>
                            <div style="position:relative">
                                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-weight:700;color:#0284c7;font-size:0.9rem">RM</span>
                                <input type="number" name="delivery_b2c_free_threshold" id="delivery_b2c_free_threshold"
                                    min="0" step="0.01" placeholder="100.00"
                                    value="{{ old('delivery_b2c_free_threshold', $settings['delivery_b2c_free_threshold'] ?? '100.00') }}"
                                    class="form-control"
                                    style="padding-left:42px;border-radius:10px;height:44px;font-size:1rem;font-weight:700;border-color:#bae6fd">
                            </div>
                            <div style="font-size:0.72rem;color:#0284c7;margin-top:6px;font-weight:600">≥ RM 100: Eligible for standard delivery</div>
                        </div>

                        {{-- Local Johor Rate (< RM100) --}}
                        <div style="background:white;border:1.5px solid #e2e8f0;border-radius:14px;padding:20px;position:relative;overflow:hidden">
                            <div style="position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#10b981,#34d399)"></div>
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
                                <div style="width:38px;height:38px;border-radius:10px;background:#d1fae5;display:flex;align-items:center;justify-content:center;font-size:1.2rem">📍</div>
                                <div>
                                    <div style="font-weight:800;font-size:0.9rem;color:#0f172a">Local Johor Area Rate</div>
                                    <div style="font-size:0.72rem;color:#64748b">JB, Skudai, Kulai, Iskandar Puteri</div>
                                </div>
                            </div>
                            <label style="font-size:0.78rem;font-weight:700;color:#374151;display:block;margin-bottom:6px">Fee for orders &lt; RM 100</label>
                            <div style="position:relative">
                                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-weight:700;color:#10b981;font-size:0.9rem">RM</span>
                                <input type="number" name="delivery_fee_zone_local" id="delivery_fee_zone_local"
                                    min="0" step="0.01" placeholder="10.00"
                                    value="{{ old('delivery_fee_zone_local', $settings['delivery_fee_zone_local'] ?? '10.00') }}"
                                    class="form-control"
                                    style="padding-left:42px;border-radius:10px;height:44px;font-size:1rem;font-weight:700;border-color:#a7f3d0">
                            </div>
                            <div style="font-size:0.72rem;color:#059669;margin-top:6px;font-weight:600">Local direct cold-chain fleet rate</div>
                        </div>

                        {{-- Outstation Peninsular Rate (< RM100) --}}
                        <div style="background:white;border:1.5px solid #e2e8f0;border-radius:14px;padding:20px;position:relative;overflow:hidden">
                            <div style="position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#f59e0b,#fbbf24)"></div>
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
                                <div style="width:38px;height:38px;border-radius:10px;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:1.2rem">🚛</div>
                                <div>
                                    <div style="font-weight:800;font-size:0.9rem;color:#0f172a">Outstation Peninsular Rate</div>
                                    <div style="font-size:0.72rem;color:#64748b">KL, Selangor, Melaka, Perak, etc.</div>
                                </div>
                            </div>
                            <label style="font-size:0.78rem;font-weight:700;color:#374151;display:block;margin-bottom:6px">Fee for orders &lt; RM 100</label>
                            <div style="position:relative">
                                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-weight:700;color:#d97706;font-size:0.9rem">RM</span>
                                <input type="number" name="delivery_fee_zone_outstation" id="delivery_fee_zone_outstation"
                                    min="0" step="0.01" placeholder="20.00"
                                    value="{{ old('delivery_fee_zone_outstation', $settings['delivery_fee_zone_outstation'] ?? '20.00') }}"
                                    class="form-control"
                                    style="padding-left:42px;border-radius:10px;height:44px;font-size:1rem;font-weight:700;border-color:#fde68a">
                            </div>
                            <div style="font-size:0.72rem;color:#b45309;margin-top:6px;font-weight:600">Sub-zero courier logistics rate</div>
                        </div>

                        {{-- Fallback Default Rate (< RM100) --}}
                        <div style="background:white;border:1.5px solid #e2e8f0;border-radius:14px;padding:20px;position:relative;overflow:hidden">
                            <div style="position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#64748b,#94a3b8)"></div>
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
                                <div style="width:38px;height:38px;border-radius:10px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-size:1.2rem">🌐</div>
                                <div>
                                    <div style="font-weight:800;font-size:0.9rem;color:#0f172a">Default Fallback Rate</div>
                                    <div style="font-size:0.72rem;color:#64748b">Other/unspecified regions</div>
                                </div>
                            </div>
                            <label style="font-size:0.78rem;font-weight:700;color:#374151;display:block;margin-bottom:6px">Fee for orders &lt; RM 100</label>
                            <div style="position:relative">
                                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-weight:700;color:#475569;font-size:0.9rem">RM</span>
                                <input type="number" name="delivery_fee_default" id="delivery_fee_default"
                                    min="0" step="0.01" placeholder="15.00"
                                    value="{{ old('delivery_fee_default', $settings['delivery_fee_default'] ?? '15.00') }}"
                                    class="form-control"
                                    style="padding-left:42px;border-radius:10px;height:44px;font-size:1rem;font-weight:700;border-color:#cbd5e1">
                            </div>
                            <div style="font-size:0.72rem;color:#475569;margin-top:6px;font-weight:600">Fallback general delivery rate</div>
                        </div>
                    </div>

                    {{-- Policy Summary Alert --}}
                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:16px 20px">
                        <div style="font-weight:700;font-size:0.86rem;color:#15803d;margin-bottom:6px;display:flex;align-items:center;gap:6px">
                            <span>✅</span> Policy Rules Summary
                        </div>
                        <ul style="margin:0;padding-left:18px;font-size:0.8rem;color:#166534;line-height:1.7">
                            <li><strong>RM 100 &amp; Above:</strong> Eligible for standard local delivery arrangement (RM 0.00 delivery fee).</li>
                            <li><strong>Below RM 100:</strong> Customers can place orders without blocking; transportation fee is calculated based on customer's delivery zone.</li>
                            <li><strong>Self-Collection / Walk-in:</strong> 100% Free with no minimum order threshold.</li>
                        </ul>
                    </div>
                </div>

                {{-- Action Button --}}
                <div style="display:flex;justify-content:flex-end;margin-top:24px">
                    <button type="submit" class="btn btn-primary" style="background:#4f46e5;border-color:#4f46e5;padding:10px 24px;font-weight:700;font-size:0.9rem;display:inline-flex;align-items:center;gap:8px">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>Save Order &amp; Delivery Settings</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>

<!-- Test Email Modal -->
<div id="testEmailModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:99999;align-items:center;justify-content:center;padding:16px">
    <div style="background:white;border-radius:12px;max-width:440px;width:100%;box-shadow:0 20px 40px rgba(0,0,0,0.25);overflow:hidden">
        <form action="{{ route('admin.settings.testEmail') }}" method="POST">
            @csrf
            <div style="padding:14px 18px;border-bottom:1px solid var(--gray-200);display:flex;justify-content:space-between;align-items:center;background:#f8fafc">
                <div style="font-weight:800;font-size:1rem;color:var(--gray-900)">✉️ Send Test Email</div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeTestEmailModal()">✕</button>
            </div>
            <div style="padding:18px">
                <label class="form-label">Recipient Email Address <span class="required">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ $settings['mail_contact_email'] ?? 'admin@mst.my' }}" required>
                <p class="text-xs text-muted mt-2">A test notification will be dispatched via the configured SMTP server.</p>
            </div>
            <div style="padding:12px 18px;border-top:1px solid var(--gray-200);display:flex;justify-content:flex-end;gap:8px;background:#f8fafc">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeTestEmailModal()">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="background:#5b5bf0;border-color:#5b5bf0">Send Test Email</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Database Import Confirmation -->
<div id="importConfirmModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,23,42,0.6);z-index:99999;align-items:center;justify-content:center;backdrop-filter:blur(3px);padding:16px;">
    <div style="background:white;border-radius:14px;width:100%;max-width:460px;padding:24px;box-shadow:0 20px 25px -5px rgba(0,0,0,0.1),0 10px 10px -5px rgba(0,0,0,0.04);">
        <div style="width:48px;height:48px;border-radius:50%;background:#ffedd5;color:#ea580c;display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin-bottom:14px;">
            ⚠️
        </div>
        <h3 style="font-size:1.15rem;font-weight:700;color:#0f172a;margin:0 0 6px;">Confirm Database Import</h3>
        <p style="font-size:0.875rem;color:#64748b;line-height:1.5;margin:0 0 16px;">
            You are about to execute the uploaded SQL file into database <strong style="color:#0f172a">`{{ config('database.connections.mysql.database', 'oceanfresh') }}`</strong>. This may overwrite or update existing tables.
        </p>
        <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:12px 14px;margin-bottom:20px;">
            <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;color:#92400e;font-weight:700;margin-bottom:4px">Selected File</div>
            <div id="importConfirmFilename" style="font-size:0.85rem;font-weight:700;color:#78350f;word-break:break-all;font-family:monospace"></div>
            <div id="importConfirmFilesize" style="font-size:0.75rem;color:#b45309;margin-top:4px"></div>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;">
            <button type="button" onclick="closeImportConfirmModal()" class="btn btn-secondary" style="flex:1;min-width:100px;">
                Cancel
            </button>
            <button type="button" id="btnExecuteImport" onclick="executeDatabaseImport()" class="btn" style="flex:1;min-width:140px;font-weight:700;background:#ea580c;border-color:#ea580c;color:#ffffff;display:inline-flex;align-items:center;justify-content:center;gap:6px">
                <span id="importBtnSpinner" style="display:none">⏳</span>
                <span id="importBtnText">Yes, Import Now</span>
            </button>
        </div>
    </div>
</div>

<!-- Modal: Database Restore Confirmation -->
<div id="restoreBackupModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,23,42,0.6);z-index:99999;align-items:center;justify-content:center;backdrop-filter:blur(3px);padding:16px;">
    <div style="background:white;border-radius:14px;width:100%;max-width:460px;padding:24px;box-shadow:0 20px 25px -5px rgba(0,0,0,0.1),0 10px 10px -5px rgba(0,0,0,0.04);">
        <form id="restoreBackupForm" method="POST">
            @csrf
            <div style="width:48px;height:48px;border-radius:50%;background:#ffedd5;color:#ea580c;display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin-bottom:14px;">
                🔄
            </div>
            <h3 style="font-size:1.15rem;font-weight:700;color:#0f172a;margin:0 0 6px;">Restore Database Archive</h3>
            <p style="font-size:0.875rem;color:#64748b;line-height:1.5;margin:0 0 16px;">
                Are you sure you want to restore the database from this stored archive? This will execute all SQL queries inside the archive.
            </p>
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 14px;margin-bottom:20px;">
                <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;font-weight:700;margin-bottom:4px">Archive File</div>
                <div id="restoreBackupFilename" style="font-size:0.85rem;font-weight:700;color:#0f172a;word-break:break-all;font-family:monospace"></div>
                <div id="restoreBackupFilesize" style="font-size:0.75rem;color:#64748b;margin-top:4px"></div>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;">
                <button type="button" onclick="closeRestoreBackupModal()" class="btn btn-secondary" style="flex:1;min-width:100px;">
                    Cancel
                </button>
                <button type="submit" id="btnExecuteRestore" class="btn" style="flex:1;min-width:140px;font-weight:700;background:#ea580c;border-color:#ea580c;color:#ffffff;display:inline-flex;align-items:center;justify-content:center;gap:6px">
                    <span>Yes, Restore</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Database Backup Delete Confirmation -->
<div id="deleteBackupModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,23,42,0.6);z-index:99999;align-items:center;justify-content:center;backdrop-filter:blur(3px);padding:16px;">
    <div style="background:white;border-radius:14px;width:100%;max-width:440px;padding:24px;box-shadow:0 20px 25px -5px rgba(0,0,0,0.1),0 10px 10px -5px rgba(0,0,0,0.04);">
        <div style="width:48px;height:48px;border-radius:50%;background:#fee2e2;color:#ef4444;display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin-bottom:14px;">
            🗑️
        </div>
        <h3 style="font-size:1.15rem;font-weight:700;color:#0f172a;margin:0 0 6px;">Delete Database Backup</h3>
        <p style="font-size:0.875rem;color:#64748b;line-height:1.5;margin:0 0 16px;">
            Are you sure you want to permanently delete this database backup? This action cannot be undone.
        </p>
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 14px;margin-bottom:20px;">
            <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;font-weight:700;margin-bottom:4px">Backup File</div>
            <div id="deleteBackupFilename" style="font-size:0.85rem;font-weight:700;color:#0f172a;word-break:break-all;font-family:monospace"></div>
            <div id="deleteBackupFilesize" style="font-size:0.75rem;color:#64748b;margin-top:4px"></div>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;">
            <button type="button" onclick="closeDeleteBackupModal()" class="btn btn-secondary" style="flex:1;min-width:100px;">
                Cancel
            </button>
            <button type="button" id="confirmDeleteBackupBtn" onclick="executeDeleteBackup()" class="btn btn-danger" style="flex:1;min-width:140px;font-weight:700;background:#ef4444;border-color:#ef4444;color:#ffffff;display:inline-flex;align-items:center;justify-content:center;gap:6px">
                <span id="deleteBackupBtnSpinner" style="display:none">⏳</span>
                <span id="deleteBackupBtnText">Yes, Delete</span>
            </button>
        </div>
    </div>
</div>

@include('components.gallery-picker-modal')
@endsection

@push('scripts')
<script>
function switchSettingsTab(tabId, event) {
    if (event) event.preventDefault();

    // Update URL hash without jumping page
    if (history.pushState) {
        history.pushState(null, null, '#' + tabId);
    } else {
        window.location.hash = tabId;
    }

    // Toggle active desktop menu items
    document.querySelectorAll('.settings-nav-item').forEach(el => {
        el.classList.remove('active');
        if (el.getAttribute('data-tab-target') === tabId) {
            el.classList.add('active');
        }
    });

    // Toggle active mobile tab pills and scroll into view
    document.querySelectorAll('.settings-mobile-tab-pill').forEach(el => {
        el.classList.remove('active');
        if (el.getAttribute('data-tab-target') === tabId) {
            el.classList.add('active');
            el.scrollIntoView({ behavior: 'smooth', inline: 'nearest', block: 'nearest' });
        }
    });

    // Toggle active content pane
    document.querySelectorAll('.settings-pane').forEach(pane => {
        pane.style.display = 'none';
    });

    const targetPane = document.getElementById(`tab-${tabId}`);
    if (targetPane) {
        targetPane.style.display = 'block';
    }
}

function handleOrderMinToggle(radio) {
    const isEnabled = radio.value === '1';

    // Update label card borders & backgrounds
    const labelEnabled  = document.getElementById('labelMinEnabled');
    const labelDisabled = document.getElementById('labelMinDisabled');
    if (labelEnabled && labelDisabled) {
        if (isEnabled) {
            labelEnabled.style.borderColor  = '#4f46e5';
            labelEnabled.style.background   = '#eef2ff';
            labelDisabled.style.borderColor = '#e2e8f0';
            labelDisabled.style.background  = 'white';
        } else {
            labelDisabled.style.borderColor = '#4f46e5';
            labelDisabled.style.background  = '#eef2ff';
            labelEnabled.style.borderColor  = '#e2e8f0';
            labelEnabled.style.background   = 'white';
        }
    }

    // Fade / enable the fields container
    const fieldsEl = document.getElementById('orderMinFields');
    if (fieldsEl) {
        fieldsEl.style.opacity        = isEnabled ? '1' : '0.5';
        fieldsEl.style.pointerEvents  = isEnabled ? 'auto' : 'none';
    }

    // Update the header badge live
    const badge = document.getElementById('orderMinBadge');
    if (badge) {
        if (isEnabled) {
            badge.textContent     = '● Minimums Active';
            badge.style.color     = '#059669';
            badge.style.background = '#ecfdf5';
            badge.style.borderColor = '#a7f3d0';
        } else {
            badge.textContent     = '○ Minimums Disabled';
            badge.style.color     = '#64748b';
            badge.style.background = '#f1f5f9';
            badge.style.borderColor = '#e2e8f0';
        }
    }
}

async function syncCurrencyRatesNow() {
    const btn = document.getElementById('btnSyncRates');
    const icon = document.getElementById('btnSyncRatesIcon');
    const text = document.getElementById('btnSyncRatesText');
    const feedback = document.getElementById('currencySyncFeedback');
    const csrf = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

    if (btn) btn.disabled = true;
    if (text) text.textContent = 'Syncing rates...';
    if (icon) icon.style.display = 'inline-block';

    try {
        const res = await fetch('{{ route("admin.settings.currency.sync") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            }
        });

        const data = await res.json();

        if (data.success && data.rates) {
            const sgdRate = parseFloat(data.rates.SGD) || 0.3117;
            const usdRate = parseFloat(data.rates.USD) || 0.2453;

            const cardSgd = document.getElementById('cardRateSgd');
            const cardUsd = document.getElementById('cardRateUsd');
            if (cardSgd) cardSgd.innerHTML = sgdRate.toFixed(4) + ' <span style="font-size:0.8rem;color:#64748b">SGD / RM</span>';
            if (cardUsd) cardUsd.innerHTML = usdRate.toFixed(4) + ' <span style="font-size:0.8rem;color:#64748b">USD / RM</span>';

            const inputSgd = document.getElementById('inputManualRateSgd');
            const inputUsd = document.getElementById('inputManualRateUsd');
            if (inputSgd) inputSgd.value = sgdRate.toFixed(4);
            if (inputUsd) inputUsd.value = usdRate.toFixed(4);

            if (feedback) {
                feedback.style.display = 'block';
                feedback.style.background = '#f0fdf4';
                feedback.style.border = '1.5px solid #86efac';
                feedback.style.color = '#15803d';
                feedback.innerHTML = '✅ ' + (data.message || 'Live exchange rates synced successfully!');
                setTimeout(() => { feedback.style.display = 'none'; }, 8000);
            }
        } else {
            document.getElementById('formSyncRates')?.submit();
        }
    } catch (err) {
        console.warn('AJAX rate sync fallback to form submit:', err);
        document.getElementById('formSyncRates')?.submit();
    } finally {
        if (btn) btn.disabled = false;
        if (text) text.textContent = 'Refresh Rates Now';
    }
}

function filterSettingsMenu(query) {
    const q = query.toLowerCase().trim();
    document.querySelectorAll('.settings-nav-item').forEach(el => {
        const text = el.textContent.toLowerCase();
        el.style.display = text.includes(q) ? 'flex' : 'none';
    });
}

function openTestEmailModal() {
    document.getElementById('testEmailModal').style.display = 'flex';
}

function closeTestEmailModal() {
    document.getElementById('testEmailModal').style.display = 'none';
}

// Generic Password Show/Hide Toggle
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    if (!input) return;
    input.type = input.type === 'password' ? 'text' : 'password';
}

// Stripe Mode Visual Radio Toggle
function toggleStripeModeVisual(mode) {
    const testLabel = document.getElementById('labelStripeModeTest');
    const liveLabel = document.getElementById('labelStripeModeLive');
    if (mode === 'test') {
        if (testLabel) {
            testLabel.style.borderColor = '#2563eb';
            testLabel.style.background = '#eff6ff';
        }
        if (liveLabel) {
            liveLabel.style.borderColor = '#e2e8f0';
            liveLabel.style.background = '#ffffff';
        }
    } else {
        if (testLabel) {
            testLabel.style.borderColor = '#e2e8f0';
            testLabel.style.background = '#ffffff';
        }
        if (liveLabel) {
            liveLabel.style.borderColor = '#16a34a';
            liveLabel.style.background = '#f0fdf4';
        }
    }
}

// SMTP Password Show/Hide Toggle
function toggleSmtpPassword() {
    const input = document.getElementById('smtp_password');
    const btn = document.getElementById('smtp-eye-btn');
    if (!input) return;
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    btn.textContent = isHidden ? '🙈' : '👁';
    btn.title = isHidden ? 'Hide password' : 'Show password';
}

// Modules Tab — active count badge + card state sync
function initModulesTab() {
    const toggles = document.querySelectorAll('.module-toggle-input');
    const countBadge = document.getElementById('modules-active-count');
    const changedHint = document.getElementById('modules-changed-hint');

    function updateCount() {
        const on = document.querySelectorAll('.module-toggle-input:checked').length;
        const total = toggles.length;
        if (countBadge) countBadge.textContent = on + ' / ' + total + ' Modules Active';
    }

    function syncCard(toggle) {
        const key = toggle.getAttribute('data-card');
        const card = document.getElementById('module-card-' + key);
        const iconWrap = document.getElementById('module-icon-' + key);
        const statusLabel = document.getElementById('module-status-' + key);
        if (!card) return;
        if (toggle.checked) {
            card.classList.add('module-card-on');
            if (iconWrap) iconWrap.classList.add('module-icon-on');
            if (statusLabel) {
                statusLabel.textContent = '✓ Active';
                statusLabel.className = 'module-status-label module-status-on';
            }
        } else {
            card.classList.remove('module-card-on');
            if (iconWrap) iconWrap.classList.remove('module-icon-on');
            if (statusLabel) {
                statusLabel.textContent = '✕ Disabled';
                statusLabel.className = 'module-status-label module-status-off';
            }
        }
    }

    toggles.forEach(toggle => {
        toggle.addEventListener('change', () => {
            syncCard(toggle);
            updateCount();
            if (changedHint) changedHint.style.display = 'inline-flex';
        });
    });

    updateCount();
}

// Live Preview Bindings for Contact Settings
function initContactLivePreview() {
    const bindPreview = (inputId, previewId, fallback) => {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        if (!input || !preview) return;
        const update = () => {
            const val = input.value.trim();
            preview.textContent = val || fallback;
        };
        input.addEventListener('input', update);
    };

    bindPreview('input_store_address', 'preview-address', '—');
    bindPreview('input_store_phone', 'preview-phone', '—');
    bindPreview('input_store_email', 'preview-email', '—');
    bindPreview('input_store_hours', 'preview-hours', '—');
}

// Auto-activate tab on page load based on URL hash or activeTab parameter
document.addEventListener('DOMContentLoaded', () => {
    let initialTab = '{{ $activeTab }}';
    if (window.location.hash) {
        const hash = window.location.hash.replace('#', '');
        if (document.getElementById(`tab-${hash}`)) {
            initialTab = hash;
        }
    }
    switchSettingsTab(initialTab);
    initContactLivePreview();
    initModulesTab();
    initAppearanceTab();
});

// ---- Appearance Tab JS ----

// Preview uploaded file immediately in the image element
function previewUpload(input, imgId, placeholderId, badgeId) {
    const img = document.getElementById(imgId);
    const placeholder = document.getElementById(placeholderId);
    const badge = document.getElementById(badgeId);
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    const reader = new FileReader();
    reader.onload = (e) => {
        if (img) { img.src = e.target.result; img.style.display = 'block'; }
        if (placeholder) placeholder.style.display = 'none';
        if (badge) {
            badge.textContent = '✓ Ready to upload — ' + file.name;
            badge.className = 'appearance-file-badge appearance-file-badge-set';
        }
    };
    reader.readAsDataURL(file);
}

// Override gallery pickers to also update the status badges
function pickSiteLogo() {
    openGalleryPicker(function(path, url, name) {
        document.getElementById('gallerySiteLogoInput').value = path;
        const img = document.getElementById('logoPreviewImg');
        const placeholder = document.getElementById('logoPlaceholder');
        const badge = document.getElementById('logo-status-badge');
        img.src = url; img.style.display = 'block';
        if (placeholder) placeholder.style.display = 'none';
        if (badge) {
            badge.textContent = '✓ Logo set — ' + name;
            badge.className = 'appearance-file-badge appearance-file-badge-set';
        }
    }, false);
}

function pickSiteFavicon() {
    openGalleryPicker(function(path, url, name) {
        document.getElementById('gallerySiteFaviconInput').value = path;
        const img = document.getElementById('faviconPreviewImg');
        const placeholder = document.getElementById('faviconPlaceholder');
        const badge = document.getElementById('favicon-status-badge');
        img.src = url; img.style.display = 'block';
        if (placeholder) placeholder.style.display = 'none';
        if (badge) {
            badge.textContent = '✓ Favicon set — ' + name;
            badge.className = 'appearance-file-badge appearance-file-badge-set';
        }
    }, false);
}

function initAppearanceTab() {
    // Live footer copyright preview
    const copyrightInput = document.getElementById('input_footer_copyright');
    const copyrightPreview = document.getElementById('preview-footer-copyright');
    if (copyrightInput && copyrightPreview) {
        copyrightInput.addEventListener('input', () => {
            const val = copyrightInput.value.trim();
            copyrightPreview.textContent = val || ('© ' + new Date().getFullYear() + ' Mika Import and Export SDN Bhd. All rights reserved.');
        });
    }
}

// ─── Database Import & Restore Functions ──────────────────────────────
function handleSqlFileSelect(input) {
    const file = input.files && input.files[0];
    const infoEl = document.getElementById('sqlFileInfo');
    const promptEl = document.getElementById('sqlFilePrompt');
    if (file) {
        const sizeFormatted = (file.size / 1024 < 1024) 
            ? (file.size / 1024).toFixed(1) + ' KB' 
            : (file.size / (1024 * 1024)).toFixed(2) + ' MB';
        if (infoEl) {
            infoEl.style.display = 'block';
            infoEl.textContent = '✓ Selected: ' + file.name + ' (' + sizeFormatted + ')';
        }
        if (promptEl) {
            promptEl.textContent = 'File ready to import';
        }
    }
}

function handleSqlFileDrop(event) {
    event.preventDefault();
    const dt = event.dataTransfer;
    if (dt && dt.files && dt.files.length) {
        const input = document.getElementById('sqlFileInput');
        if (input) {
            input.files = dt.files;
            handleSqlFileSelect(input);
        }
    }
}

function promptImportConfirm() {
    const input = document.getElementById('sqlFileInput');
    if (!input || !input.files || !input.files.length) {
        alert('Please select a .sql or .sql.gz file to import first.');
        input && input.click();
        return;
    }
    const file = input.files[0];
    const nameEl = document.getElementById('importConfirmFilename');
    const sizeEl = document.getElementById('importConfirmFilesize');
    const modal = document.getElementById('importConfirmModal');

    if (nameEl) nameEl.textContent = file.name;
    if (sizeEl) {
        const sizeFormatted = (file.size / 1024 < 1024) 
            ? (file.size / 1024).toFixed(1) + ' KB' 
            : (file.size / (1024 * 1024)).toFixed(2) + ' MB';
        sizeEl.textContent = 'File Size: ' + sizeFormatted;
    }
    if (modal) modal.style.display = 'flex';
}

function closeImportConfirmModal() {
    const modal = document.getElementById('importConfirmModal');
    if (modal) modal.style.display = 'none';
}

function executeDatabaseImport() {
    const btn = document.getElementById('btnExecuteImport');
    const spinner = document.getElementById('importBtnSpinner');
    const text = document.getElementById('importBtnText');
    if (btn) btn.disabled = true;
    if (spinner) spinner.style.display = 'inline-block';
    if (text) text.textContent = 'Importing SQL... Please wait';

    document.getElementById('dbImportForm')?.submit();
}

function openRestoreBackupModal(filename, actionUrl, fileSize) {
    const modal = document.getElementById('restoreBackupModal');
    const form = document.getElementById('restoreBackupForm');
    const nameEl = document.getElementById('restoreBackupFilename');
    const sizeEl = document.getElementById('restoreBackupFilesize');

    if (form) form.action = actionUrl;
    if (nameEl) nameEl.textContent = filename;
    if (sizeEl) sizeEl.textContent = fileSize ? 'File Size: ' + fileSize : '';
    if (modal) modal.style.display = 'flex';
}

function closeRestoreBackupModal() {
    const modal = document.getElementById('restoreBackupModal');
    if (modal) modal.style.display = 'none';
}

function promptMigrateConfirm() {
    if (confirm('Run pending database migrations (migrate --force)? This will create or update any missing database tables.')) {
        document.getElementById('formRunMigrations')?.submit();
    }
}

// Database Backup Delete Functions
let pendingDeleteBackup = null;

function openDeleteBackupModal(filename, actionUrl, rowId, fileSize) {
    pendingDeleteBackup = { filename, actionUrl, rowId };
    const modal = document.getElementById('deleteBackupModal');
    const nameEl = document.getElementById('deleteBackupFilename');
    const sizeEl = document.getElementById('deleteBackupFilesize');
    
    if (modal && nameEl) {
        nameEl.textContent = filename;
        if (sizeEl) sizeEl.textContent = fileSize ? 'File Size: ' + fileSize : '';
        modal.style.display = 'flex';
    }
}

function closeDeleteBackupModal() {
    const modal = document.getElementById('deleteBackupModal');
    if (modal) modal.style.display = 'none';
    pendingDeleteBackup = null;
    const btn = document.getElementById('confirmDeleteBackupBtn');
    const spinner = document.getElementById('deleteBackupBtnSpinner');
    const text = document.getElementById('deleteBackupBtnText');
    if (btn) btn.disabled = false;
    if (spinner) spinner.style.display = 'none';
    if (text) text.textContent = 'Yes, Delete';
}

function executeDeleteBackup() {
    if (!pendingDeleteBackup) return;
    const { filename, actionUrl, rowId } = pendingDeleteBackup;
    
    const btn = document.getElementById('confirmDeleteBackupBtn');
    const spinner = document.getElementById('deleteBackupBtnSpinner');
    const text = document.getElementById('deleteBackupBtnText');
    if (btn) btn.disabled = true;
    if (spinner) spinner.style.display = 'inline-block';
    if (text) text.textContent = 'Deleting...';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_token"]')?.value 
        || '';

    fetch(actionUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ _method: 'DELETE' })
    })
    .then(async response => {
        const data = await response.json().catch(() => ({}));
        if (response.ok && data.success) {
            closeDeleteBackupModal();
            showAdminToast(data.message || `Database backup '${filename}' deleted successfully.`, 'success');
            
            // Smoothly remove row from table without reloading
            const row = document.getElementById(rowId);
            if (row) {
                row.style.transition = 'all 0.35s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateX(20px)';
                setTimeout(() => {
                    row.remove();
                    const tbody = document.getElementById('databaseBackupsTbody');
                    if (tbody && tbody.querySelectorAll('tr').length === 0) {
                        const tableCard = document.getElementById('databaseBackupsCard');
                        if (tableCard) {
                            tableCard.outerHTML = '<div id="noBackupsMessage" style="margin-top:16px;padding:24px;text-align:center;color:#64748b;font-size:0.875rem;background:#ffffff;border:1px solid #e2e8f0;border-radius:8px">No backup archives currently stored on server.</div>';
                        }
                    }
                }, 350);
            }
        } else {
            throw new Error(data.message || 'Failed to delete backup file.');
        }
    })
    .catch(err => {
        if (btn) btn.disabled = false;
        if (spinner) spinner.style.display = 'none';
        if (text) text.textContent = 'Yes, Delete';
        showAdminToast(err.message || 'An error occurred while deleting backup.', 'error');
    });
}

function showAdminToast(message, type = 'success') {
    let container = document.getElementById('adminToastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'adminToastContainer';
        container.style.cssText = 'position:fixed;top:24px;right:24px;z-index:100000;display:flex;flex-direction:column;gap:10px;max-width:440px;width:calc(100% - 48px);pointer-events:none;';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    const isSuccess = type === 'success';
    toast.style.cssText = `
        background: ${isSuccess ? '#ffffff' : '#ffffff'};
        border: 1px solid ${isSuccess ? '#bbf7d0' : '#fecaca'};
        border-left: 4px solid ${isSuccess ? '#16a34a' : '#dc2626'};
        color: ${isSuccess ? '#15803d' : '#b91c1c'};
        padding: 14px 18px;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.12), 0 8px 10px -6px rgba(0,0,0,0.08);
        pointer-events: auto;
        transform: translateY(-12px);
        opacity: 0;
        transition: all 0.25s ease-out;
    `;

    toast.innerHTML = `
        <span style="display:flex;align-items:center;gap:10px">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;border-radius:50%;background:${isSuccess ? '#dcfce7' : '#fee2e2'};color:${isSuccess ? '#16a34a' : '#dc2626'};font-size:0.8rem;flex-shrink:0">${isSuccess ? '✓' : '⚠️'}</span>
            <span>${message}</span>
        </span>
        <button type="button" style="background:transparent;border:none;color:#94a3b8;cursor:pointer;font-size:1.1rem;padding:0;line-height:1;margin-left:8px" onmouseover="this.style.color='#0f172a'" onmouseout="this.style.color='#94a3b8'">✕</button>
    `;

    const closeBtn = toast.querySelector('button');
    closeBtn.onclick = () => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        setTimeout(() => toast.remove(), 250);
    };

    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    });

    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            setTimeout(() => toast.remove(), 250);
        }
    }, 4000);
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteBackupModal();
    }
});
</script>
@endpush

@push('styles')
<style>
/* Standardized Switch Toggle Component */
.switch-toggle {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 26px;
    flex-shrink: 0;
    margin: 0;
    vertical-align: middle;
}
.switch-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
    position: absolute;
}
.switch-toggle .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: background-color 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 34px;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
}
.switch-toggle .slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background-color: #ffffff;
    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 50%;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
}
.switch-toggle input:checked + .slider {
    background-color: #10b981 !important;
}
.switch-toggle input:checked + .slider:before {
    transform: translateX(24px) !important;
}
.switch-toggle input:focus + .slider {
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
}

/* Desktop Menu Items */
.settings-nav-item:hover {
    background: #f1f5f9;
}
.settings-nav-item.active {
    background: #4f46e5 !important;
    color: #ffffff !important;
}

/* Responsive Form Grids */
.settings-form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.settings-form-grid-3 {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 16px;
}

.settings-input {
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.settings-input:focus {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
}

/* Contact Live Preview Grid */
.contact-preview-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}
.contact-preview-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 14px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
    transition: all 0.2s ease;
}
.contact-preview-item:hover {
    border-color: #cbd5e1;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}
.contact-preview-icon {
    font-size: 1.25rem;
    line-height: 1;
    flex-shrink: 0;
    margin-top: 2px;
}
.contact-preview-label {
    font-weight: 700;
    font-size: 0.74rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 2px;
}
.contact-preview-value {
    color: #1e293b;
    font-size: 0.85rem;
    line-height: 1.45;
    word-break: break-word;
}
.contact-preview-value.blue {
    color: #2563eb;
    font-weight: 600;
}

/* Mobile Tabs Navigation */
.settings-mobile-tabs-container {
    display: none;
    margin-bottom: 18px;
}
.settings-mobile-tabs-scroll {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    padding-bottom: 6px;
    scrollbar-width: thin;
}
.settings-mobile-tabs-scroll::-webkit-scrollbar {
    height: 4px;
}
.settings-mobile-tabs-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.settings-mobile-tab-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 9999px;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    font-size: 0.84rem;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    white-space: nowrap;
    flex-shrink: 0;
    transition: all 0.15s ease;
}
.settings-mobile-tab-pill:hover {
    background: #f8fafc;
    color: #1e293b;
    border-color: #cbd5e1;
}
.settings-mobile-tab-pill.active {
    background: #4f46e5 !important;
    border-color: #4f46e5 !important;
    color: #ffffff !important;
    box-shadow: 0 2px 6px rgba(79, 70, 229, 0.25);
}

/* Breakpoints */
@media (max-width: 1024px) {
    .settings-layout-grid {
        grid-template-columns: 1fr !important;
    }
    .settings-sidebar-card {
        display: none !important;
    }
    .settings-mobile-tabs-container {
        display: block !important;
    }
}

@media (max-width: 768px) {
    .settings-form-grid-2,
    .settings-form-grid-3 {
        grid-template-columns: 1fr !important;
        gap: 14px !important;
    }
    .settings-contact-save-btn {
        width: 100% !important;
        justify-content: center !important;
    }
    /* SMTP action row mobile */
    .smtp-action-row {
        flex-direction: column !important;
        gap: 10px !important;
    }
    .smtp-action-right {
        flex-direction: column !important;
        width: 100% !important;
    }
    .smtp-btn-test,
    .smtp-btn-save,
    .smtp-btn-reset {
        width: 100% !important;
        justify-content: center !important;
    }
}

@media (max-width: 600px) {
    .contact-preview-grid {
        grid-template-columns: 1fr !important;
    }
}

/* SMTP-specific typography */
.smtp-label {
    font-weight: 700;
    color: #1e293b;
    font-size: 0.875rem;
    margin-bottom: 6px;
}
.smtp-hint {
    font-size: 0.75rem;
    color: #64748b;
    margin-top: 5px;
    line-height: 1.4;
}
.smtp-code {
    background: #f1f5f9;
    padding: 1px 6px;
    border-radius: 4px;
    color: #0f766e;
    font-weight: 600;
    font-size: 0.78rem;
}

/* SMTP Action Row */
.smtp-action-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    padding-top: 4px;
}
.smtp-action-left {
    display: flex;
    gap: 8px;
}
.smtp-action-right {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

/* ===== MODULES TAB ===== */
.modules-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
    margin-bottom: 16px;
}
@media (max-width: 860px) {
    .modules-grid {
        grid-template-columns: 1fr !important;
    }
}

/* Module card */
.module-card {
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px;
    background: #ffffff;
    transition: all 0.2s ease;
    cursor: default;
}
.module-card:hover {
    border-color: #c7d2fe;
    box-shadow: 0 2px 8px rgba(79,70,229,0.06);
}
.module-card-on {
    border-color: #a5f3c0 !important;
    background: #f0fdf4 !important;
}
.module-card-on:hover {
    border-color: #6ee7a7 !important;
    box-shadow: 0 2px 8px rgba(16,185,129,0.08) !important;
}

/* Module icon */
.module-icon-wrap {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.2s ease;
}
.module-icon-on {
    background: #dcfce7 !important;
}

/* Module text */
.module-title {
    font-weight: 700;
    color: #0f172a;
    font-size: 0.88rem;
    line-height: 1.3;
}
.module-desc {
    font-size: 0.78rem;
    color: #64748b;
    line-height: 1.5;
}

/* Module badge */
.module-badge {
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    white-space: nowrap;
    flex-shrink: 0;
}

/* Module status label */
.module-status-label {
    font-size: 0.75rem;
    font-weight: 700;
    border-radius: 20px;
    padding: 3px 10px;
}
.module-status-on {
    color: #065f46;
    background: #d1fae5;
}
.module-status-off {
    color: #64748b;
    background: #f1f5f9;
}

/* Module toggle */
.module-toggle-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
    cursor: pointer;
    flex-shrink: 0;
}
.module-toggle-input {
    opacity: 0;
    width: 0;
    height: 0;
    position: absolute;
}
.module-toggle-track {
    position: relative;
    width: 50px;
    height: 27px;
    background: #cbd5e1;
    border-radius: 999px;
    transition: background 0.25s ease;
    display: block;
}
.module-toggle-input:checked + .module-toggle-track {
    background: #10b981;
}
.module-toggle-thumb {
    position: absolute;
    top: 3px;
    left: 3px;
    width: 21px;
    height: 21px;
    background: white;
    border-radius: 50%;
    transition: transform 0.25s ease;
    box-shadow: 0 1px 4px rgba(0,0,0,0.18);
}
.module-toggle-input:checked + .module-toggle-track .module-toggle-thumb {
    transform: translateX(23px);
}

/* ===== APPEARANCE TAB ===== */
.appearance-assets-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
@media (max-width: 768px) {
    .appearance-assets-grid {
        grid-template-columns: 1fr !important;
    }
}

.appearance-asset-card {
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px;
    background: #fafbfc;
    display: flex;
    flex-direction: column;
    gap: 0;
    transition: border-color 0.2s;
}
.appearance-asset-card:hover {
    border-color: #c7d2fe;
}

.appearance-asset-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
    gap: 8px;
}

.appearance-gallery-btn {
    flex-shrink: 0;
    font-size: 0.78rem !important;
    padding: 5px 12px !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
}

.appearance-preview-box {
    width: 100%;
    height: 110px;
    background: white;
    border: 1.5px dashed #cbd5e1;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    transition: border-color 0.2s;
}
.appearance-preview-box:hover {
    border-color: #a5b4fc;
}

.appearance-logo-img {
    max-height: 80px;
    max-width: 90%;
    object-fit: contain;
}
.appearance-favicon-img {
    width: 40px;
    height: 40px;
    object-fit: contain;
}
.appearance-placeholder {
    font-weight: 700;
    color: #94a3b8;
    font-size: 1rem;
}

.appearance-file-badge {
    font-size: 0.73rem;
    font-weight: 600;
    border-radius: 6px;
    padding: 5px 10px;
    margin-top: 8px;
    line-height: 1.3;
    word-break: break-all;
}
.appearance-file-badge-set {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}
.appearance-file-badge-empty {
    background: #f1f5f9;
    color: #64748b;
    border: 1px solid #e2e8f0;
}

.appearance-file-input {
    font-size: 0.82rem !important;
    border-radius: 8px !important;
    padding: 6px 10px !important;
}

.appearance-hint {
    font-size: 0.73rem;
    color: #94a3b8;
    margin-top: 5px;
    line-height: 1.4;
}
</style>
@endpush