<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register your named middleware here
       $middleware->alias([
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'role:instructor' => \App\Http\Middleware\EnsureUserIsInstructor::class,
    'role:student' => \App\Http\Middleware\EnsureUserIsStudent::class,
    'check.user.status' => \App\Http\Middleware\CheckUserStatus::class,
]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
