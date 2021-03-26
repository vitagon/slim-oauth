<?php

declare(strict_types=1);

use App\Http\Middleware\JsonBodyParserMiddleware;
use App\Http\Middleware\SessionMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\App;

return static function (App $app, ContainerInterface $container) {
    $app->add(SessionMiddleware::class);
    $app->add(JsonBodyParserMiddleware::class);
//    $app->add(CorsMiddleware::class);
    $app->addRoutingMiddleware();

    $app->addErrorMiddleware(
        $container->get('config')['debug'],
        true,
        true,
        $app->getContainer()->get(LoggerInterface::class)
    );
};
