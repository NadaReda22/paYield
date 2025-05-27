<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\DenyAdminAccess;
use App\Http\Middleware\SetLocale;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin.access'=>DenyAdminAccess::class,
            'locale'=>SetLocale::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

           // Apply it globally
    $middleware->appendToGroup('web', [
        \App\Http\Middleware\SetLocale::class,
    ]);
   
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
