<?php

declare(strict_types=1);

use App\Http\Middleware\CorsMiddleware;
use Psr\Container\ContainerInterface;
use Slim\App;
use DI\Bridge\Slim\Bridge as AppFactory;

return static function (ContainerInterface $container): App {
    $app = AppFactory::create($container);
    (require __DIR__ . '/middleware.php')($app, $container);
    (require __DIR__ . '/routes.php')($app);
    return $app;
};
