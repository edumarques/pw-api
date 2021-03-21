<?php

declare(strict_types=1);

use Laravel\Lumen\Routing\Router;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/** @var Router $router */

$router->get('/', fn() => $router->app->version());

$router->group(
    ['prefix' => '/api/v1'],
    function () use ($router): void {
        /**
         * Configuration routes
         * @see \App\Http\Controllers\ConfigController
         */
        $router->group(
            ['prefix' => '/config'],
            function () use ($router): void {
                $router->get('/status', 'ConfigController@status');
            }
        );

        /**
         * Portfolio routes
         * @see \App\Http\Controllers\PortfolioController
         */
        $router->group(
            ['prefix' => '/portfolio'],
            function () use ($router): void {
                $router->get('/user', 'PortfolioController@user');
                $router->get('/repos', 'PortfolioController@repos');
            }
        );
    }
);
