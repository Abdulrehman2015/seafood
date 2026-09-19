<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $settings['store_name'] ?? 'MST Import and Export Sdn Bhd' }}</title>

    {{-- Dynamic Favicon --}}
    @if(!empty($settings['site_favicon']))
        <link rel="icon" type="image/webp" href="{{ asset('storage/' . $settings['site_favicon']) }}">
        <link rel="shortcut icon" href="{{ asset('storage/' . $settings['site_favicon']) }}">
    @else
        <link rel="icon" type="image/webp" href="{{ asset('images/favicon.webp') }}">
        <link rel="shortcut icon" href="{{ asset('images/favicon.webp') }}">
    @endif

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- Site CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        body {
            font-family: 'Inter', 'Outfit', sans-serif;
            background: linear-gradient(135deg, #f0fdfa 0%, #eff6ff 50%, #f8fafc 100%);
            min-height: 100vh;
            margin: 0;
        }

        .guest-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }

        .guest-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            text-decoration: none;
        }

        .guest-brand img {
            height: 52px;
            width: 52px;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 100, 80, 0.15);
        }

        .guest-brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .guest-brand-name {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.1rem;
            color: #0f766e;
            letter-spacing: -0.02em;
        }

        .guest-brand-sub {
            font-size: 0.65rem;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .guest-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.04);
            padding: 36px 36px 32px;
            border: 1px solid rgba(203, 213, 225, 0.5);
        }

        @media (max-width: 480px) {
            .guest-card {
                padding: 28px 20px;
                border-radius: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="guest-wrapper">

        {{-- Dynamic Brand Logo & Name --}}
        <a href="{{ url('/') }}" class="guest-brand">
            @if(!empty($settings['site_logo']))
                <img src="{{ asset('storage/' . $settings['site_logo']) }}"
                     alt="{{ $settings['store_name'] ?? 'MST Import and Export Sdn Bhd' }}">
            @else
                <img src="{{ asset('images/logo.webp') }}"
                     alt="{{ $settings['store_name'] ?? 'MST Import and Export Sdn Bhd' }}">
            @endif
            <div class="guest-brand-text">
                <span class="guest-brand-name">{{ $settings['store_name'] ?? 'MST Import and Export Sdn Bhd' }}</span>
                <span class="guest-brand-sub">{{ $settings['store_tagline'] ?? 'Flow with Integrity, Grow with Strength' }}</span>
            </div>
        </a>

        {{-- Auth Card --}}
        <div class="guest-card">
            {{ $slot }}
        </div>

    </div>
</body>

</html>
