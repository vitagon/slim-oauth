<?php

declare(strict_types=1);

use App\Http\Action\AccessTokenAction;
use App\Http\Action\AuthorizationAction;
use App\Http\Action\ClientAction;
use App\Http\Action\HomeAction;
use App\Http\Action\JwtAction;
use App\Http\Action\LoginAction;
use App\Http\Action\Options\OptionsAction;
use App\Http\Action\ProfileAction;
use App\Http\Middleware\ResourceServerMiddleware;
use League\OAuth2\Server\ResourceServer;
use Slim\App;
use Slim\Middleware\Authentication\JwtAuthentication;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->get('/', HomeAction::class);

    $app->post('/login', LoginAction::class);

    $app->get('/profile', ProfileAction::class)
        ->add($app->getContainer()->get(JwtAuthentication::class));

    $app->get('/client', ClientAction::class);

    $app->group('/oauth', function (RouteCollectorProxy $group) use ($app) {
        $group->post('/access_token', AccessTokenAction::class);
        $group->get('/authorize', AuthorizationAction::class)
            ->add(new ResourceServerMiddleware($app->getContainer()->get(ResourceServer::class)));
    });

    $app->get('/jwt', JwtAction::class);

    $app->options('/{routes:.+}', OptionsAction::class);
};
