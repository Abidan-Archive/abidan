<?php

use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \SocialiteProviders\Manager\ServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        // channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(AppServiceProvider::HOME);

        $middleware->web(\App\Http\Middleware\HandleInertiaRequests::class);

        $middleware->throttleApi();

        $middleware->appendToGroup('web', [
            \App\Http\Middleware\EnsureLowercaseUri::class,
            \App\Http\Middleware\CompressResponse::class,
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureHasRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            // Inertia to handle some errors for custom error pages
            $disabledInertiaErrorHandlingEnvs = ['local', 'testing'];
            if (! app()->environment($disabledInertiaErrorHandlingEnvs) && in_array($response->getStatusCode(), [500, 503, 404, 403])) {
                return Inertia::render('Error', ['status' => $response->getStatusCode()])
                    ->toResponse($request)
                    ->setStatusCode($response->getStatusCode());
            }

            // CSRF token expired with inertia
            if ($response->getStatusCode() === 419) {
                return back()->with([
                    'flash' => [
                        'message' => 'The page expired, please try again.',
                        'type' => 'error',
                    ],
                ]);
            }

            return $response;
        });
    })->create();
