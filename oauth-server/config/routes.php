<?php

declare(strict_types=1);

use App\Http\Action\AccessTokenAction;
use App\Http\Action\AuthorizationAction;
use App\Http\Action\ClientAction;
use App\Http\Action\HomeAction;
use App\Http\Action\JwtAction;
use App\Http\Action\LoginAction;
use App\Http\Action\LogoutAction;
use App\Http\Action\Options\OptionsAction;
use App\Http\Action\ProfileAction;
use App\Http\Middleware\ResourceServerMiddleware;
use App\Http\Middleware\WebAuthMiddleware;
use League\OAuth2\Server\ResourceServer;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $webAuthMiddleware = $app->getContainer()->get(WebAuthMiddleware::class);
    $oAuthMiddleware = new ResourceServerMiddleware($app->getContainer()->get(ResourceServer::class));

    $app->get('/', HomeAction::class);
    $app->post('/api/login', LoginAction::class);
    $app->post('/api/logout', LogoutAction::class);
    $app->get('/api/profile', ProfileAction::class)->addMiddleware($webAuthMiddleware);
    $app->get('/client', ClientAction::class)
        ->addMiddleware($oAuthMiddleware);

    $app->group('/oauth', function (RouteCollectorProxy $group) use ($webAuthMiddleware, $app) {
        $group->post('/access_token', AccessTokenAction::class);
        $group->get('/authorize', AuthorizationAction::class);
    })->addMiddleware($webAuthMiddleware);

    $app->get('/jwt', JwtAction::class);

    $app->options('/{routes:.+}', OptionsAction::class);
};
