<?php

use App\Http\Middleware\WebAuthMiddleware;
use App\Http\Security\SecurityContext;
use App\Service\AuthService;
use App\Service\UserService;
use Psr\Container\ContainerInterface;
use Slim\Middleware\Authentication\JwtAuthentication;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

return [
    'config' => [
        'auth' => [
            'secret' => 'app_secret'
        ],
    ],

    WebAuthMiddleware::class => function (ContainerInterface $container) {
        $session = $container->get(SessionInterface::class);
        $securityContext = $container->get(SecurityContext::class);
        return new WebAuthMiddleware($session, $securityContext);
    },
    JwtAuthentication::class => function (ContainerInterface $container) {
        return new JwtAuthentication($container, [
            'secure' => false,
            'secret' => $container->get('config')['auth']['secret'],
        ]);
    },
    AuthService::class => function (ContainerInterface $container) {
        $userService = $container->get(UserService::class);
        $jwtSecret = $container->get('config')['auth']['secret'];

        return new AuthService($userService, $jwtSecret);
    },
];