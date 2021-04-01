<?php

use App\OAuth\Bridge\Repository\AccessTokenRepository;
use App\OAuth\Bridge\Repository\AuthCodeRepository;
use App\OAuth\Bridge\Repository\ClientRepository;
use App\OAuth\Bridge\Repository\RefreshTokenRepository;
use App\OAuth\Bridge\Repository\ScopeRepository;
use App\OAuth\Bridge\Repository\UserRepository;
use Defuse\Crypto\Key;
use League\OAuth2\Server\AuthorizationServer;
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
use Psr\Container\ContainerInterface;

return [
    'config' => [
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
    ],

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
];