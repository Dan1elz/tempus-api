<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            // Se você não estiver usando SPA authentication e quiser garantir que não há cookies de sessão
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // Comente ou remova se for APENAS token puro
        ]);

        // Exemplo: Adicionar um middleware a todas as rotas da API
        // $middleware->api(\App\Http\Middleware\MeuMiddlewareCustomizado::class);

        // Exemplo: Proteger rotas específicas com Sanctum no grupo 'api' se não estiver usando nas rotas individuais
        // $middleware->alias([
        //     'auth:sanctum' => \Laravel\Sanctum\Http\Middleware\Authenticate::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ...
    })->create();
