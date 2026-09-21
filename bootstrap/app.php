<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->append(\App\Http\Middleware\SecurityHeadersMiddleware::class);

        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            $locale = session('locale', $request->cookie('locale', config('app.locale', 'en')));
            if (!in_array($locale, ['en', 'zh', 'bm'])) {
                $locale = 'en';
            }
            return route('login', ['locale' => $locale]);
        });

        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            $user = $request->user();
            if ($user && $user->isAdmin()) {
                return route('admin.dashboard');
            }

            $locale = session('locale', $request->cookie('locale', config('app.locale', 'en')));
            if (!in_array($locale, ['en', 'zh', 'bm'])) {
                $locale = 'en';
            }
            return route('home', ['locale' => $locale]);
        });

        $middleware->web(append: [
            \App\Http\Middleware\SetLocaleMiddleware::class,
            \App\Http\Middleware\PerformanceHeadersMiddleware::class,
            \App\Http\Middleware\EnsurePendingUserRestricted::class,
        ]);

        $middleware->alias([
            'admin'    => \App\Http\Middleware\AdminMiddleware::class,
            'approved' => \App\Http\Middleware\ApprovedCustomerMiddleware::class,
            'walkin'   => \App\Http\Middleware\WalkInMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your session has expired. Please refresh and try again.',
                ], 419);
            }

            return redirect()->route('home')->with('info', 'Your session has expired. You have been redirected to the homepage.');
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() === 419) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Your session has expired. Please refresh and try again.',
                    ], 419);
                }

                return redirect()->route('home')->with('info', 'Your session has expired. You have been redirected to the homepage.');
            }
        });
    })->create();
