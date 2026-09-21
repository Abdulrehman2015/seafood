{{-- GDPR Cookie Consent Banner (InspectWP & EU/UK/MY Privacy Compliant) --}}
<div id="cookie-banner" class="cookie-banner cookie-consent cookieconsent" data-cookie-banner="true" role="region" aria-label="Cookie Consent" style="display:none;">
    <div class="cookie-banner-card">
        <div class="cookie-banner-content">
            <div class="cookie-banner-icon" aria-hidden="true">🍪</div>
            <div class="cookie-banner-text">
                <div class="cookie-banner-title">
                    @t('cookie.banner_title', 'We value your privacy')
                </div>
                <p class="cookie-banner-desc">
                    @t('cookie.banner_desc', 'We use essential cookies to make our store work properly, and optional cookies to remember your preferred language and currency. We do not sell your personal information.')
                    <a href="{{ url(current_locale() . '/privacy') }}" class="cookie-banner-link" target="_blank">
                        @t('cookie.learn_more', 'Privacy & Cookie Policy')
                    </a>
                </p>
            </div>
        </div>
        <div class="cookie-banner-actions">
            <button type="button" id="cookie-reject" class="cookie-btn cookie-btn-reject cookie-consent-reject" data-cookie-reject="true">
                @t('cookie.essential_only', 'Essential Only')
            </button>
            <button type="button" id="cookie-accept" class="cookie-btn cookie-btn-accept cookie-consent-accept" data-cookie-accept="true">
                @t('cookie.accept_all', 'Accept All')
            </button>
        </div>
    </div>
</div>

<style>
/* ─── GDPR Cookie Banner Styles ─── */
.cookie-banner {
    position: fixed;
    bottom: 24px;
    left: 24px;
    right: 24px;
    max-width: 680px;
    margin: 0 auto;
    z-index: 999999;
    animation: cookieSlideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes cookieSlideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.cookie-banner-card {
    background: rgba(10, 25, 48, 0.94);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(147, 197, 253, 0.25);
    border-radius: 18px;
    padding: 20px 24px;
    box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    color: #ffffff;
}

.cookie-banner-content {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    flex: 1;
}

.cookie-banner-icon {
    font-size: 1.8rem;
    line-height: 1;
    flex-shrink: 0;
}

.cookie-banner-title {
    font-weight: 700;
    font-size: 0.95rem;
    color: #ffffff;
    margin-bottom: 4px;
    font-family: inherit;
}

.cookie-banner-desc {
    margin: 0;
    font-size: 0.82rem;
    line-height: 1.5;
    color: #94a3b8;
}

.cookie-banner-link {
    color: #38bdf8;
    text-decoration: underline;
    text-underline-offset: 2px;
    font-weight: 500;
    transition: color 0.15s ease;
}

.cookie-banner-link:hover {
    color: #7dd3fc;
}

.cookie-banner-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.cookie-btn {
    border: none;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.82rem;
    padding: 10px 18px;
    border-radius: 10px;
    transition: all 0.18s ease;
    white-space: nowrap;
    outline: none;
}

.cookie-btn-reject {
    background: rgba(255, 255, 255, 0.08);
    color: #cbd5e1;
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.cookie-btn-reject:hover {
    background: rgba(255, 255, 255, 0.14);
    color: #ffffff;
}

.cookie-btn-accept {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
}

.cookie-btn-accept:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(37, 99, 235, 0.45);
}

@media (max-width: 768px) {
    .cookie-banner {
        bottom: 12px;
        left: 12px;
        right: 12px;
    }
    .cookie-banner-card {
        flex-direction: column;
        align-items: stretch;
        padding: 16px 18px;
        gap: 16px;
    }
    .cookie-banner-actions {
        width: 100%;
        justify-content: flex-end;
    }
    .cookie-btn {
        flex: 1;
        text-align: center;
    }
}
</style>

<script>
(function() {
    var banner = document.getElementById('cookie-banner');
    if (!banner) return;

    var consentKey = 'cookie_consent';
    function getConsent() {
        try {
            var val = localStorage.getItem(consentKey);
            if (val) return val;
        } catch(e) {}
        var match = document.cookie.match(new RegExp('(^| )' + consentKey + '=([^;]+)'));
        return match ? match[2] : null;
    }

    function setConsent(status) {
        try {
            localStorage.setItem(consentKey, status);
        } catch(e) {}
        var isSecure = window.location.protocol === 'https:';
        var expires = new Date(Date.now() + 365 * 24 * 60 * 60 * 1000).toUTCString();
        document.cookie = consentKey + '=' + status + '; expires=' + expires + '; path=/; SameSite=Lax' + (isSecure ? '; Secure' : '');
        banner.style.display = 'none';
    }

    if (!getConsent()) {
        // Show after slight delay for smooth page entrance
        setTimeout(function() {
            banner.style.display = 'block';
        }, 500);
    }

    var acceptBtn = document.getElementById('cookie-accept');
    var rejectBtn = document.getElementById('cookie-reject');

    if (acceptBtn) {
        acceptBtn.addEventListener('click', function() {
            setConsent('accepted');
        });
    }
    if (rejectBtn) {
        rejectBtn.addEventListener('click', function() {
            setConsent('essential');
        });
    }
})();
</script>
