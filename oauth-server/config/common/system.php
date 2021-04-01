<?php

declare(strict_types=1);

use App\Http\Kernel\ResponseFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

return [
    'config' => [
        'debug' => (bool)getenv('APP_DEBUG'),
        'session' => [
            'name' => 'slim_oauth',
            'cache_expire' => 0,
            'use_cookies' => 0,
        ],
    ],

    ResponseFactoryInterface::class => DI\get(ResponseFactory::class),
    Session::class => function (ContainerInterface $container) {
        $settings = $container->get('config')['session'];
        if (PHP_SAPI === 'cli') {
            return new Session(new MockArraySessionStorage());
        }

        return new Session(new NativeSessionStorage($settings, new NativeFileSessionHandler()));
    },
    SessionInterface::class => DI\get(Session::class),
];
