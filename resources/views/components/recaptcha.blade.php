@props(['context' => null, 'align' => 'left'])

@if(\App\Models\Setting::isRecaptchaEnabled($context))
    @php
        $siteKey = \App\Models\Setting::getRecaptchaSiteKey();
        $hl = match(app()->getLocale()) {
            'zh' => 'zh-CN',
            'bm' => 'ms',
            default => 'en'
        };
    @endphp

    @once
        @push('scripts')
            <script src="https://www.google.com/recaptcha/api.js?hl={{ $hl }}" async defer></script>
        @endpush
    @endonce

    <div class="recaptcha-wrapper" style="margin: 16px 0; {{ $align === 'center' ? 'display:flex;flex-direction:column;align-items:center;' : '' }}">
        <div class="g-recaptcha" data-sitekey="{{ $siteKey }}" style="display:inline-block;"></div>
        @error('g-recaptcha-response')
            <div class="form-error recaptcha-error" style="color:#ef4444;font-size:0.82rem;font-weight:600;margin-top:6px;display:flex;align-items:center;gap:5px;">
                <span>⚠️</span>
                <span>{{ $message }}</span>
            </div>
        @enderror
    </div>
@endif
