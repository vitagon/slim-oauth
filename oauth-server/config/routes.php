<?php

declare(strict_types=1);

use App\Http\Action\AccessTokenAction;
use App\Http\Action\AuthorizationAction;
use App\Http\Action\ClientAction;
use App\Http\Action\DbTestAction;
use App\Http\Action\HomeAction;
use App\Http\Action\JwtAction;
use App\Http\Action\LoginController;
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
    $app->get('/login', [LoginController::class, 'show']);

    $app->group('/api', function (RouteCollectorProxy $group) use ($webAuthMiddleware, $oAuthMiddleware) {
//        $group->post('/login', [LoginController::class, 'login']);
        $group->post('/logout', LogoutAction::class);
        $group->get('/profile', ProfileAction::class)->addMiddleware($webAuthMiddleware);
        $group->get('/client', ClientAction::class)->addMiddleware($oAuthMiddleware);
        $group->get('/dbtest', DbTestAction::class);
    });

    $app->group('/oauth', function (RouteCollectorProxy $group) use ($webAuthMiddleware, $app) {
        $group->post('/access_token', AccessTokenAction::class);
        $group->get('/authorize', AuthorizationAction::class);
    })->addMiddleware($webAuthMiddleware);

    $app->get('/jwt', JwtAction::class);

    $app->options('/{routes:.+}', OptionsAction::class);
};
