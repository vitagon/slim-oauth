<?php

use DI\Container;
use DI\ContainerBuilder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpException;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

http_response_code(500);

$builder = new ContainerBuilder();

$builder->addDefinitions([
    'config' => [
//        'debug' => (bool)getenv('APP_DEBUG')
        'debug' => true
    ]
]);

$container = $builder->build();

$app = AppFactory::createFromContainer($container);

$app->addErrorMiddleware($container->get('config')['debug'], true, true);
//$app->addErrorMiddleware(true, true, true);

$app->get('/', \App\Http\Action\HomeAction::class);

$app->run();