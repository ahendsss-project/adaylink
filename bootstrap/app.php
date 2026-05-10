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
        $middleware->alias([
            'subscription.active' => \App\Http\Middleware\EnsureSubscriptionActive::class,
            'subdomain.validate' => \App\Http\Middleware\ValidateSubdomain::class,
            'not.blocked' => \App\Http\Middleware\EnsureNotBlocked::class,
            'custom_domain.resolve' => \App\Http\Middleware\ResolveCustomDomain::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
