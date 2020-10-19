<?php

namespace App\Http;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        ValidatePostSize::class,
        ConvertEmptyStringsToNull::class,
        // \App\Http\Middleware\TrustProxies::class, // for laravel 5.5
    ];


    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class, // родная миделварка не умеет в подмену ттл сессии
            ShareErrorsFromSession::class,
            SubstituteBindings::class,
        ],

        'ajax' => [
            // расшарим состояние сессии web для ajax
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class, // родная миделварка не умеет в подмену ттл сессии
            SubstituteBindings::class,
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => Authenticate::class,
        'auth.login' => Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'bindings' => SubstituteBindings::class,
        'can' => Authorize::class,
        'throttle' => ThrottleRequests::class,
    ];

    protected $middlewarePriority = [
        StartSession::class, // родная миделварка не умеет в подмену ттл сессии
        ShareErrorsFromSession::class,
        Authenticate::class,
        SubstituteBindings::class,
        Authorize::class,
    ];
}
