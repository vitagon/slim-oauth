<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseFactoryInterface;

return [
    'config' => [
//        'debug' => (bool)getenv('APP_DEBUG')
        'debug' => true
    ],
    ResponseFactoryInterface::class => Di\get(\Slim\Psr7\Factory\ResponseFactory::class)
];
