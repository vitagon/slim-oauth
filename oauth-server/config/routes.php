<?php

declare(strict_types=1);

use App\Http\Action\AccessTokenAction;
use App\Http\Action\ApproveAuthorizationController;
use App\Http\Action\AuthorizationController;
use App\Http\Action\ClientAction;
use App\Http\Action\DbTestAction;
use App\Http\Action\HomeController;
use App\Http\Action\JwtAction;
use App\Http\Action\LoginController;
use App\Http\Action\LogoutController;
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

    $app->get('/', HomeController::class);
    $app->get('/login', [LoginController::class, 'show']);
    $app->post('/login', [LoginController::class, 'login']);
    $app->get('/logout', LogoutController::class);

    $app->group('/api', function (RouteCollectorProxy $group) use ($webAuthMiddleware, $oAuthMiddleware) {
        $group->get('/profile', ProfileAction::class)->addMiddleware($webAuthMiddleware);
        $group->get('/dbtest', DbTestAction::class);
    });

    $app->group('/oauth', function (RouteCollectorProxy $group) use ($webAuthMiddleware, $oAuthMiddleware) {
        $group->post('/access_token', AccessTokenAction::class);
        $group->get('/authorize', AuthorizationController::class)->addMiddleware($webAuthMiddleware);
        $group->post('/authorize', ApproveAuthorizationController::class)->addMiddleware($webAuthMiddleware);
        $group->get('/user', ClientAction::class)->addMiddleware($oAuthMiddleware);
    });

    $app->get('/jwt', JwtAction::class);

    $app->options('/{routes:.+}', OptionsAction::class);
};
