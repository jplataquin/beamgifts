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
            'city.context' => \App\Http\Middleware\SetCityContext::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            '/hitpay/webhook',
        ]);

        $middleware->redirectTo(
            guests: '/login',
            users: function ($request) {
                if ($request->is('admin/*')) {
                    return route('admin.login');
                }
                if ($request->is('partner/*')) {
                    return route('partner.login');
                }
                return route('login');
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
