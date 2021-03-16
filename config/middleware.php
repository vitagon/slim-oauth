<?php

declare(strict_types=1);

use App\Http\Middleware\SessionMiddleware;
use Psr\Container\ContainerInterface;
use Slim\App;

return static function (App $app, ContainerInterface $container) {
    $app->add(SessionMiddleware::class);
    $app->addErrorMiddleware($container->get('config')['debug'], true, true);
    $app->addRoutingMiddleware();
};
