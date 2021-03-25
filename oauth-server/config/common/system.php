<?php

declare(strict_types=1);

use App\Http\Logger\CustomLineFormatter;
use App\OAuth\Repository\AccessTokenRepository;
use App\OAuth\Repository\AuthCodeRepository;
use App\OAuth\Repository\ClientRepository;
use App\OAuth\Repository\RefreshTokenRepository;
use App\OAuth\Repository\ScopeRepository;
use App\OAuth\Repository\UserRepository;
use App\Service\AuthService;
use App\Service\UserService;
use Defuse\Crypto\Key;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ImplicitGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\ResourceServer;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\Middleware\Authentication\JwtAuthentication;
use App\Http\Kernel\ResponseFactory;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

return [
    'config' => [
        'debug' => (bool)getenv('APP_DEBUG'),
        'session' => [
            'name' => 'slimo_auth',
            'cache_expire' => 0,
            'use_cookies' => 0,
        ],
        'auth' => [
            'secret' => 'app_secret'
        ],
        'oauth' => [
            'private_key_path' => sprintf(
                '%s%s',
                'file://',
                realpath(__DIR__ . '/../../storage/keys/oauth/private.key')
            ),

            'public_key_path' => sprintf(
                '%s%s',
                'file://',
                realpath(__DIR__ . '/../../storage/keys/oauth/public.key')
            ),

            // this key should be moved out of git
            'encryption_key' => 'def00000f155cfea59d464403bcfc897e5fdbb444ca874cba8f6f5a507694f'
                                . 'b07e0fd636dda59e51f5b0deb3c16669c7e9ac2bc72d9de21e2c41e0033edecc20d653f024',
        ],
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : realpath(__DIR__ . '/../../logs/app.log'),
            'level' => Logger::DEBUG,
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
    ClientRepositoryInterface::class => DI\get(ClientRepository::class),
    AccessTokenRepositoryInterface::class => DI\get(AccessTokenRepository::class),
    ScopeRepositoryInterface::class => DI\get(ScopeRepository::class),
    UserRepositoryInterface::class => DI\get(UserRepository::class),
    RefreshTokenRepositoryInterface::class => DI\get(RefreshTokenRepository::class),
    AuthCodeRepositoryInterface::class => DI\get(AuthCodeRepository::class),

    AuthorizationServer::class => function (ContainerInterface $container) {
        $clientRepository = $container->get(ClientRepositoryInterface::class);
        $accessTokenRepository = $container->get(AccessTokenRepositoryInterface::class);
        $scopeRepository = $container->get(ScopeRepositoryInterface::class);
        $userRepository = $container->get(UserRepositoryInterface::class);
        $refreshTokenRepository = $container->get(RefreshTokenRepositoryInterface::class);
        $authCodeRepository = $container->get(AuthCodeRepositoryInterface::class);
        $privateKey = $container->get('config')['oauth']['private_key_path'];

        $server = new AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $privateKey,
            Key::loadFromAsciiSafeString($container->get('config')['oauth']['encryption_key'])
        );

        $passwordGrant = new PasswordGrant(
            $userRepository,
            $refreshTokenRepository
        );
        $passwordGrant->setRefreshTokenTTL(new DateInterval('P1M')); // refresh tokens will expire after 1 month
        $server->enableGrantType(
            $passwordGrant,
            new DateInterval('PT1H') // access tokens will expire after 1 hour
        );

        $grant = new AuthCodeGrant(
            $authCodeRepository,
            $refreshTokenRepository,
            new DateInterval('PT10M') // authorization codes will expire after 10 minutes
        );
        $grant->setRefreshTokenTTL(new DateInterval('P1M')); // refresh tokens will expire after 1 month
        $server->enableGrantType(
            $grant,
            new DateInterval('PT1H') // access tokens will expire after 1 hour
        );

        $refreshTokenGrant = new RefreshTokenGrant($refreshTokenRepository);
        $refreshTokenGrant->setRefreshTokenTTL(new DateInterval('P1M')); // The refresh token will expire in 1 month

        $server->enableGrantType(
            $refreshTokenGrant,
            new DateInterval('PT1H') // The new access token will expire after 1 hour
        );

        $server->enableGrantType(
            new ImplicitGrant(new DateInterval('PT1H')),
            new DateInterval('PT1H')
        );

        return $server;
    },
    ResourceServer::class => function (ContainerInterface $container) {
        $accessTokenRepository = $container->get(AccessTokenRepositoryInterface::class);
        $publicKey = $container->get('config')['oauth']['public_key_path'];

        return new ResourceServer($accessTokenRepository, $publicKey);
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
    LoggerInterface::class => function (ContainerInterface $container) {
        $loggerSettings = $container->get('config')['logger'];
        $logger = new Logger($loggerSettings['name']);

        $processor = new UidProcessor();
        $logger->pushProcessor($processor);

        $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
        $lineFormatter = new CustomLineFormatter(null, null, true);
        $handler->setFormatter($lineFormatter);
        $logger->pushHandler($handler);

        return $logger;
    },
];
