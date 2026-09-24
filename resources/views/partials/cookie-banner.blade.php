{{-- MST Privacy & Cookie Consent Banner + Preferences Panel --}}
<div id="cookie-banner" class="cookie-banner-wrapper" role="region" aria-label="Privacy & Cookie Notice" style="display:none;">
    <div class="cookie-banner-card">
        <div class="cookie-banner-body">
            <div class="cookie-banner-icon" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
            </div>
            <div class="cookie-banner-text">
                <h3 class="cookie-banner-title">
                    @t('cookie.banner_title', 'We value your privacy')
                </h3>
                <p class="cookie-banner-desc">
                    @t('cookie.banner_desc', 'We use essential cookies to make our website work properly. Optional cookies help us remember your preferences, such as language and currency. We do not sell your personal information.')
                    <span class="cookie-banner-links">
                        <a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => 'privacy-policy']) }}" class="cookie-legal-link" target="_blank" rel="noopener">@t('cookie.privacy_policy', 'Privacy Policy')</a>
                        <span class="cookie-dot-sep">·</span>
                        <a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => 'cookie-policy']) }}" class="cookie-legal-link" target="_blank" rel="noopener">@t('cookie.cookie_policy', 'Cookie Policy')</a>
                    </span>
                </p>
            </div>
        </div>
        <div class="cookie-banner-actions">
            <button type="button" id="cookie-btn-essential" class="cookie-btn cookie-btn-essential">
                @t('cookie.essential_only', 'Essential Only')
            </button>
            <button type="button" id="cookie-btn-accept" class="cookie-btn cookie-btn-accept">
                @t('cookie.accept_all', 'Accept All')
            </button>
            <button type="button" id="cookie-btn-settings" class="cookie-btn cookie-btn-settings">
                @t('cookie.cookie_settings', 'Cookie Settings')
            </button>
        </div>
    </div>
</div>

{{-- Cookie Preferences Modal Panel --}}
<div id="cookie-settings-modal" class="cookie-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="cookie-modal-title" style="display:none;">
    <div class="cookie-modal-card">
        <div class="cookie-modal-header">
            <div>
                <h2 id="cookie-modal-title" class="cookie-modal-title">
                    @t('cookie.modal_title', 'Cookie Preferences')
                </h2>
                <p class="cookie-modal-subtitle">
                    @t('cookie.modal_desc', 'Manage your cookie preferences. Essential cookies are required for site security, navigation and basic features. You can choose whether to enable optional cookies.')
                </p>
            </div>
            <button type="button" class="cookie-modal-close" id="cookie-modal-close" aria-label="@t('cookie.close', 'Close')">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="cookie-modal-body">
            {{-- Category 1: Essential Cookies (Always Active) --}}
            <div class="cookie-cat-item">
                <div class="cookie-cat-header">
                    <div class="cookie-cat-info">
                        <div class="cookie-cat-title-row">
                            <span class="cookie-cat-title">@t('cookie.cat_essential_title', 'Essential Cookies')</span>
                            <span class="cookie-badge cookie-badge-active">@t('cookie.cat_essential_status', 'Always Active')</span>
                        </div>
                        <p class="cookie-cat-desc">
                            @t('cookie.cat_essential_desc', 'Required for core website functionality, security, session management, and shopping cart operations. Cannot be disabled.')
                        </p>
                    </div>
                    <div class="cookie-toggle-wrap">
                        <label class="cookie-toggle-switch is-locked" title="@t('cookie.cat_essential_status', 'Always Active')">
                            <input type="checkbox" checked disabled aria-label="@t('cookie.cat_essential_title', 'Essential Cookies')">
                            <span class="cookie-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Category 2: Preference Cookies (Optional) --}}
            <div class="cookie-cat-item">
                <div class="cookie-cat-header">
                    <div class="cookie-cat-info">
                        <div class="cookie-cat-title-row">
                            <span class="cookie-cat-title">@t('cookie.cat_preferences_title', 'Preference Cookies')</span>
                            <span class="cookie-badge cookie-badge-optional">@t('cookie.cat_preferences_status', 'Optional')</span>
                        </div>
                        <p class="cookie-cat-desc">
                            @t('cookie.cat_preferences_desc', 'Enables the website to remember your personal choices, such as selected language (EN / ZH / BM) and display currency (MYR / SGD / USD).')
                        </p>
                    </div>
                    <div class="cookie-toggle-wrap">
                        <label class="cookie-toggle-switch">
                            <input type="checkbox" id="cookie-pref-preferences" aria-label="@t('cookie.cat_preferences_title', 'Preference Cookies')">
                            <span class="cookie-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Category 3: Analytics Cookies (Optional) --}}
            <div class="cookie-cat-item">
                <div class="cookie-cat-header">
                    <div class="cookie-cat-info">
                        <div class="cookie-cat-title-row">
                            <span class="cookie-cat-title">@t('cookie.cat_analytics_title', 'Analytics Cookies')</span>
                            <span class="cookie-badge cookie-badge-optional">@t('cookie.cat_preferences_status', 'Optional')</span>
                        </div>
                        <p class="cookie-cat-desc">
                            @t('cookie.cat_analytics_desc', 'Helps us understand how visitors interact with the website to improve user experience. No personal identifying information is collected without consent.')
                        </p>
                    </div>
                    <div class="cookie-toggle-wrap">
                        <label class="cookie-toggle-switch">
                            <input type="checkbox" id="cookie-pref-analytics" aria-label="@t('cookie.cat_analytics_title', 'Analytics Cookies')">
                            <span class="cookie-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Category 4: Third-Party Cookies (Optional) --}}
            <div class="cookie-cat-item">
                <div class="cookie-cat-header">
                    <div class="cookie-cat-info">
                        <div class="cookie-cat-title-row">
                            <span class="cookie-cat-title">@t('cookie.cat_third_party_title', 'Third-Party Technologies')</span>
                            <span class="cookie-badge cookie-badge-optional">@t('cookie.cat_preferences_status', 'Optional')</span>
                        </div>
                        <p class="cookie-cat-desc">
                            @t('cookie.cat_third_party_desc', 'Integrated third-party services such as interactive maps, communication or security widgets where enabled.')
                        </p>
                    </div>
                    <div class="cookie-toggle-wrap">
                        <label class="cookie-toggle-switch">
                            <input type="checkbox" id="cookie-pref-third-party" aria-label="@t('cookie.cat_third_party_title', 'Third-Party Technologies')">
                            <span class="cookie-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="cookie-modal-footer">
            <div class="cookie-modal-footer-links">
                <a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => 'privacy-policy']) }}" class="cookie-legal-link" target="_blank" rel="noopener">@t('cookie.privacy_policy', 'Privacy Policy')</a>
                <span class="cookie-dot-sep">·</span>
                <a href="{{ route('policy.show', ['locale' => app()->getLocale(), 'slug' => 'cookie-policy']) }}" class="cookie-legal-link" target="_blank" rel="noopener">@t('cookie.cookie_policy', 'Cookie Policy')</a>
            </div>
            <div class="cookie-modal-footer-actions">
                <button type="button" class="cookie-btn cookie-btn-cancel" id="cookie-modal-cancel">
                    @t('cookie.close', 'Close')
                </button>
                <button type="button" class="cookie-btn cookie-btn-save" id="cookie-modal-save">
                    @t('cookie.save_preferences', 'Save Preferences')
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* ─── MST Privacy & Cookie Banner Styles ─── */
.cookie-banner-wrapper {
    position: fixed;
    bottom: 20px;
    left: 20px;
    right: 20px;
    max-width: 640px;
    margin: 0 auto;
    z-index: 999990;
    pointer-events: none; /* Allows clicks around the banner so normal browsing is non-blocking */
    animation: mstCookieSlideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes mstCookieSlideUp {
    from {
        opacity: 0;
        transform: translateY(18px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.cookie-banner-card {
    pointer-events: auto;
    background: rgba(10, 26, 48, 0.96);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border: 1px solid rgba(147, 197, 253, 0.22);
    border-radius: 16px;
    padding: 16px 20px;
    box-shadow: 0 16px 36px -6px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.06);
    display: flex;
    flex-direction: column;
    gap: 14px;
    color: #ffffff;
}

.cookie-banner-body {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.cookie-banner-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: rgba(37, 99, 235, 0.15);
    border: 1px solid rgba(56, 189, 248, 0.3);
    color: #38bdf8;
    flex-shrink: 0;
    margin-top: 1px;
}

.cookie-banner-text {
    flex: 1;
    min-width: 0;
}

.cookie-banner-title {
    font-size: 0.92rem;
    font-weight: 700;
    color: #f8fafc;
    margin: 0 0 4px 0;
    line-height: 1.3;
    letter-spacing: -0.01em;
}

.cookie-banner-desc {
    margin: 0;
    font-size: 0.81rem;
    line-height: 1.5;
    color: #cbd5e1;
}

.cookie-banner-links {
    display: inline-block;
    margin-left: 4px;
    white-space: nowrap;
}

.cookie-dot-sep {
    color: #64748b;
    margin: 0 4px;
}

.cookie-legal-link {
    color: #38bdf8;
    text-decoration: underline;
    text-underline-offset: 2px;
    font-weight: 500;
    transition: color 0.15s ease;
}

.cookie-legal-link:hover {
    color: #7dd3fc;
}

.cookie-banner-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.cookie-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid transparent;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.82rem;
    padding: 8px 16px;
    border-radius: 8px;
    transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
    white-space: nowrap;
    outline: none;
    font-family: inherit;
    line-height: 1.3;
    text-align: center;
}

/* Comparable prominence for Essential Only and Accept All */
.cookie-btn-essential {
    background: rgba(255, 255, 255, 0.08);
    color: #f1f5f9;
    border-color: rgba(255, 255, 255, 0.18);
}

.cookie-btn-essential:hover {
    background: rgba(255, 255, 255, 0.16);
    color: #ffffff;
    border-color: rgba(255, 255, 255, 0.3);
}

.cookie-btn-accept {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 2px 10px rgba(37, 99, 235, 0.3);
}

.cookie-btn-accept:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
}

.cookie-btn-settings {
    background: transparent;
    color: #94a3b8;
    border-color: rgba(148, 163, 184, 0.25);
    margin-left: auto;
}

.cookie-btn-settings:hover {
    background: rgba(255, 255, 255, 0.05);
    color: #f1f5f9;
    border-color: rgba(148, 163, 184, 0.45);
}

/* ─── Cookie Preferences Modal ─── */
.cookie-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(4, 13, 27, 0.7);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 999995;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    animation: mstFadeIn 0.2s ease forwards;
}

@keyframes mstFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.cookie-modal-card {
    background: #0f233d;
    border: 1px solid rgba(147, 197, 253, 0.25);
    border-radius: 18px;
    max-width: 580px;
    width: 100%;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.65);
    color: #ffffff;
    overflow: hidden;
    animation: mstModalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes mstModalPop {
    from {
        opacity: 0;
        transform: scale(0.96) translateY(10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.cookie-modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    padding: 20px 24px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(255, 255, 255, 0.02);
}

.cookie-modal-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 6px 0;
    line-height: 1.3;
}

.cookie-modal-subtitle {
    margin: 0;
    font-size: 0.82rem;
    line-height: 1.5;
    color: #94a3b8;
}

.cookie-modal-close {
    background: transparent;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 4px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
    flex-shrink: 0;
}

.cookie-modal-close:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.1);
}

.cookie-modal-body {
    padding: 18px 24px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.cookie-cat-item {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 14px 16px;
}

.cookie-cat-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 14px;
}

.cookie-cat-info {
    flex: 1;
}

.cookie-cat-title-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 4px;
}

.cookie-cat-title {
    font-size: 0.92rem;
    font-weight: 700;
    color: #f1f5f9;
}

.cookie-badge {
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 999px;
    letter-spacing: 0.02em;
    text-transform: uppercase;
}

.cookie-badge-active {
    background: rgba(16, 185, 129, 0.15);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.cookie-badge-optional {
    background: rgba(56, 189, 248, 0.12);
    color: #7dd3fc;
    border: 1px solid rgba(56, 189, 248, 0.25);
}

.cookie-cat-desc {
    margin: 0;
    font-size: 0.78rem;
    line-height: 1.5;
    color: #94a3b8;
}

/* Custom Toggle Switch */
.cookie-toggle-wrap {
    flex-shrink: 0;
    margin-top: 2px;
}

.cookie-toggle-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
    cursor: pointer;
}

.cookie-toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.cookie-slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background-color: rgba(255, 255, 255, 0.18);
    transition: 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.cookie-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 2px;
    bottom: 2px;
    background-color: #ffffff;
    transition: 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
}

.cookie-toggle-switch input:checked + .cookie-slider {
    background-color: #2563eb;
    border-color: #3b82f6;
}

.cookie-toggle-switch input:checked + .cookie-slider:before {
    transform: translateX(20px);
}

.cookie-toggle-switch.is-locked {
    cursor: not-allowed;
    opacity: 0.85;
}

.cookie-toggle-switch.is-locked input:checked + .cookie-slider {
    background-color: #059669;
    border-color: #10b981;
}

.cookie-modal-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 16px 24px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(255, 255, 255, 0.02);
    flex-wrap: wrap;
}

.cookie-modal-footer-links {
    font-size: 0.78rem;
    color: #64748b;
}

.cookie-modal-footer-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-left: auto;
}

.cookie-btn-cancel {
    background: rgba(255, 255, 255, 0.06);
    color: #cbd5e1;
    border-color: rgba(255, 255, 255, 0.12);
}

.cookie-btn-cancel:hover {
    background: rgba(255, 255, 255, 0.12);
    color: #ffffff;
}

.cookie-btn-save {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 2px 10px rgba(37, 99, 235, 0.3);
}

.cookie-btn-save:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
}

/* ─── Mobile Responsiveness ─── */
@media (max-width: 640px) {
    .cookie-banner-wrapper {
        bottom: 12px;
        left: 10px;
        right: 10px;
    }
    .cookie-banner-card {
        padding: 14px 16px;
        gap: 12px;
    }
    .cookie-banner-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    .cookie-btn-settings {
        grid-column: 1 / -1;
        margin-left: 0;
        width: 100%;
    }
    .cookie-modal-card {
        max-height: 95vh;
        border-radius: 14px;
    }
    .cookie-modal-header,
    .cookie-modal-body,
    .cookie-modal-footer {
        padding: 14px 16px;
    }
    .cookie-modal-footer {
        flex-direction: column;
        align-items: stretch;
    }
    .cookie-modal-footer-actions {
        width: 100%;
        margin-left: 0;
    }
    .cookie-btn-cancel,
    .cookie-btn-save {
        flex: 1;
    }
}
</style>

<script>
(function() {
    var STORAGE_KEY = 'mst_cookie_consent';
    var LEGACY_KEY = 'cookie_consent';
    var BANNER_DELAY = 450; // ms

    function getStoredConsent() {
        var raw = null;
        try {
            raw = localStorage.getItem(STORAGE_KEY) || localStorage.getItem(LEGACY_KEY);
        } catch(e) {}

        if (!raw) {
            var match = document.cookie.match(new RegExp('(^|;\\s*)(' + STORAGE_KEY + '|' + LEGACY_KEY + ')=([^;]+)'));
            if (match) {
                raw = decodeURIComponent(match[3]);
            }
        }

        if (!raw) return null;

        try {
            var parsed = JSON.parse(raw);
            if (parsed && typeof parsed === 'object') {
                return parsed;
            }
        } catch(e) {}

        // Backwards compatibility with plain string 'accepted' or 'essential'
        if (raw === 'accepted' || raw === 'all') {
            return { essential: true, preferences: true, analytics: true, third_party: true, status: 'all' };
        } else if (raw === 'essential' || raw === 'rejected') {
            return { essential: true, preferences: false, analytics: false, third_party: false, status: 'essential' };
        }

        return null;
    }

    function persistConsent(data) {
        var consentObj = {
            essential: true,
            preferences: Boolean(data.preferences),
            analytics: Boolean(data.analytics),
            third_party: Boolean(data.third_party),
            status: data.status || (data.preferences && data.analytics && data.third_party ? 'all' : (data.preferences || data.analytics || data.third_party ? 'custom' : 'essential')),
            timestamp: Date.now(),
            version: '1.0'
        };

        var jsonStr = JSON.stringify(consentObj);

        try {
            localStorage.setItem(STORAGE_KEY, jsonStr);
            localStorage.setItem(LEGACY_KEY, consentObj.status);
        } catch(e) {}

        var isSecure = window.location.protocol === 'https:';
        var expires = new Date(Date.now() + 365 * 24 * 60 * 60 * 1000).toUTCString();
        var cookieOpts = '; expires=' + expires + '; path=/; SameSite=Lax' + (isSecure ? '; Secure' : '');
        document.cookie = STORAGE_KEY + '=' + encodeURIComponent(jsonStr) + cookieOpts;
        document.cookie = LEGACY_KEY + '=' + encodeURIComponent(consentObj.status) + cookieOpts;

        hideBanner();
        closeSettingsModal();

        window.dispatchEvent(new CustomEvent('mst:cookie-consent-updated', { detail: consentObj }));
        return consentObj;
    }

    function showBanner() {
        var banner = document.getElementById('cookie-banner');
        if (banner) {
            banner.style.display = 'block';
        }
    }

    function hideBanner() {
        var banner = document.getElementById('cookie-banner');
        if (banner) {
            banner.style.display = 'none';
        }
    }

    function openSettingsModal() {
        var modal = document.getElementById('cookie-settings-modal');
        if (!modal) return;

        var current = getStoredConsent();
        var prefToggle = document.getElementById('cookie-pref-preferences');
        var analyticsToggle = document.getElementById('cookie-pref-analytics');
        var thirdPartyToggle = document.getElementById('cookie-pref-third-party');

        if (prefToggle) {
            prefToggle.checked = current ? Boolean(current.preferences) : true;
        }
        if (analyticsToggle) {
            analyticsToggle.checked = current ? Boolean(current.analytics) : false;
        }
        if (thirdPartyToggle) {
            thirdPartyToggle.checked = current ? Boolean(current.third_party) : false;
        }

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeSettingsModal() {
        var modal = document.getElementById('cookie-settings-modal');
        if (modal) {
            modal.style.display = 'none';
        }
        document.body.style.overflow = '';
    }

    // Public API
    window.getCookieConsent = getStoredConsent;
    window.hasCookieConsent = function(category) {
        var consent = getStoredConsent();
        if (!consent) return false;
        if (category === 'essential') return true;
        return Boolean(consent[category]);
    };
    window.setCookieConsent = function(type, options) {
        options = options || {};
        if (type === 'all' || type === 'accepted') {
            return persistConsent({ preferences: true, analytics: true, third_party: true, status: 'all' });
        } else if (type === 'essential' || type === 'rejected') {
            return persistConsent({ preferences: false, analytics: false, third_party: false, status: 'essential' });
        } else {
            return persistConsent({
                preferences: Boolean(options.preferences),
                analytics: Boolean(options.analytics),
                third_party: Boolean(options.third_party),
                status: 'custom'
            });
        }
    };
    window.openCookieSettings = openSettingsModal;
    window.closeCookieSettings = closeSettingsModal;

    // Initialization
    var currentConsent = getStoredConsent();
    if (!currentConsent) {
        setTimeout(function() {
            if (!getStoredConsent()) {
                showBanner();
            }
        }, BANNER_DELAY);
    }

    // Event delegation
    document.addEventListener('click', function(e) {
        // Essential Only
        if (e.target.closest('#cookie-btn-essential, [data-cookie-reject="true"]')) {
            e.preventDefault();
            window.setCookieConsent('essential');
        }
        // Accept All
        else if (e.target.closest('#cookie-btn-accept, [data-cookie-accept="true"]')) {
            e.preventDefault();
            window.setCookieConsent('all');
        }
        // Open Settings
        else if (e.target.closest('#cookie-btn-settings, [data-cookie-settings="true"]')) {
            e.preventDefault();
            openSettingsModal();
        }
        // Close Modal via X or Close button
        else if (e.target.closest('#cookie-modal-close, #cookie-modal-cancel')) {
            e.preventDefault();
            closeSettingsModal();
        }
        // Save Modal Preferences
        else if (e.target.closest('#cookie-modal-save')) {
            e.preventDefault();
            var prefToggle = document.getElementById('cookie-pref-preferences');
            var analyticsToggle = document.getElementById('cookie-pref-analytics');
            var thirdPartyToggle = document.getElementById('cookie-pref-third-party');
            window.setCookieConsent('custom', {
                preferences: prefToggle ? prefToggle.checked : false,
                analytics: analyticsToggle ? analyticsToggle.checked : false,
                third_party: thirdPartyToggle ? thirdPartyToggle.checked : false
            });
        }
        // Backdrop Click
        else if (e.target.id === 'cookie-settings-modal') {
            closeSettingsModal();
        }
    });

    // Keyboard accessibility: Escape to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            var modal = document.getElementById('cookie-settings-modal');
            if (modal && modal.style.display !== 'none' && window.getComputedStyle(modal).display !== 'none') {
                closeSettingsModal();
            }
        }
    });
})();
</script>
