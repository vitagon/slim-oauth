<?php

declare(strict_types=1);

use App\Http\Action\AuthorizationAction;
use App\Http\Action\HomeAction;
use App\Http\Action\JwtAction;
use App\Http\Action\LoginAction;
use App\Http\Action\ProfileAction;
use Slim\App;
use Slim\Middleware\Authentication\JwtAuthentication;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->get('/', HomeAction::class);
    $app->post('/login', LoginAction::class);
    $app->get('/profile', ProfileAction::class)
        ->add($app->getContainer()->get(JwtAuthentication::class));

    $app->group('/oauth', function (RouteCollectorProxy $group) {
        $group->get('/authorize', AuthorizationAction::class);
    })->add($app->getContainer()->get(JwtAuthentication::class));

    $app->get('/jwt', JwtAction::class);
//    $app->get('/jwt/validate', \App\Http\Action\ValidateJwtAction::class);
};
