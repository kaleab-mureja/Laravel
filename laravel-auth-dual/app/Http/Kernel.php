
protected $middlewareGroups = [
    'web' => [
        // ... Laravel's default web middleware (should be uncommented)
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],

    'api' => [
        // This middleware ensures that requests from your configured stateful domains
        // will have session state and CSRF protection.
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // <-- UNCOMMENT OR ADD THIS LINE
        'throttle:api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];
