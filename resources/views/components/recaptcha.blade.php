@props(['context' => null, 'align' => 'left'])

@if(\App\Models\Setting::isRecaptchaEnabled($context))
    @php
        $siteKey = \App\Models\Setting::getRecaptchaSiteKey();
        $hl = match(app()->getLocale()) {
            'zh' => 'zh-CN',
            'bm' => 'ms',
            default => 'en'
        };
        $errBag = $errors ?? session('errors');
        $hasRecaptchaError = $errBag instanceof \Illuminate\Support\ViewErrorBag && $errBag->has('g-recaptcha-response');
        $recaptchaErrMsg = $hasRecaptchaError ? $errBag->first('g-recaptcha-response') : null;
        $uniqueId = 'recaptcha_' . uniqid();
    @endphp

    <div class="recaptcha-wrapper" style="margin: 18px 0; min-height: 78px; {{ $align === 'center' ? 'display:flex;flex-direction:column;align-items:center;' : '' }}">
        <div class="g-recaptcha" id="{{ $uniqueId }}" data-sitekey="{{ $siteKey }}" style="display:inline-block;"></div>
        @if($hasRecaptchaError)
            <div class="form-error recaptcha-error" style="color:#ef4444;font-size:0.84rem;font-weight:600;margin-top:6px;display:flex;align-items:center;gap:6px;">
                <span>⚠️</span>
                <span>{{ $recaptchaErrMsg }}</span>
            </div>
        @endif
    </div>

    <script>
        (function() {
            var hl = '{{ $hl }}';
            var siteKey = '{{ $siteKey }}';
            var targetId = '{{ $uniqueId }}';

            function renderThis() {
                var el = document.getElementById(targetId);
                if (!el) return;
                if ((!el.hasChildNodes() || el.children.length === 0) && typeof grecaptcha !== 'undefined' && typeof grecaptcha.render === 'function') {
                    try {
                        grecaptcha.render(el, { 'sitekey': siteKey });
                    } catch (e) {}
                }
            }

            if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.render === 'function') {
                setTimeout(renderThis, 50);
            } else {
                var scriptId = 'google-recaptcha-script';
                var s = document.getElementById(scriptId);
                if (!s) {
                    s = document.createElement('script');
                    s.id = scriptId;
                    s.src = 'https://www.google.com/recaptcha/api.js?hl=' + encodeURIComponent(hl);
                    s.async = true;
                    s.defer = true;
                    s.onload = function() { setTimeout(renderThis, 100); };
                    document.head.appendChild(s);
                } else {
                    var count = 0;
                    var t = setInterval(function() {
                        count++;
                        if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.render === 'function') {
                            clearInterval(t);
                            renderThis();
                        } else if (count > 30) {
                            clearInterval(t);
                        }
                    }, 150);
                }
            }
        })();
    </script>
@endif
