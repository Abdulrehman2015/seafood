<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="0;url={{ route('home') }}">
    <title>Session Expired — Redirecting...</title>
    <script>
        window.location.replace("{{ route('home') }}");
    </script>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background: #f8fafc;
            color: #1e293b;
        }
        .expired-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 32px;
            text-align: center;
            max-width: 440px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        .spinner {
            width: 36px;
            height: 36px;
            border: 3px solid #e2e8f0;
            border-top-color: #0d7377;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 16px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        h2 {
            font-size: 1.15rem;
            margin-bottom: 8px;
            color: #0f172a;
        }
        p {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 16px;
        }
        a {
            display: inline-block;
            background: #0d7377;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 8px 18px;
            border-radius: 8px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="expired-card">
        <div class="spinner"></div>
        <h2>Session Expired</h2>
        <p>Your session has expired. Redirecting you to the homepage...</p>
        <a href="{{ route('home') }}">Click here if not redirected</a>
    </div>
</body>
</html>
