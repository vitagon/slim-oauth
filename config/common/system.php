<?php

declare(strict_types=1);

use App\OAuth\Repository\AccessTokenRepository;
use App\OAuth\Repository\ScopeRepository;
use Defuse\Crypto\Key;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Key\LocalFileReference;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\ImplicitGrant;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

return [
    'config' => [
//        'debug' => (bool)getenv('APP_DEBUG')
        'debug' => true,
        'session' => [
            'name' => 'slimo_auth',
            'cache_expire' => 0,
            'use_cookies' => 0,
        ],
        'auth' => [
            'signing_key' => realpath(__DIR__ . '/../../storage/keys/oauth/private.key'),
            'verification_key' => 'mBC5v1sOKVvbdEitdSBenu59nfNfhwkedkJVNabosTw=',
        ],
        'oauth' => [
            'private_key_path' => realpath(__DIR__ . '/../../storage/keys/oauth/private.key'),

            // this key should be moved out of git
            'encryption_key' => 'def00000f155cfea59d464403bcfc897e5fdbb444ca874cba8f6f5a507694f'
                                . 'b07e0fd636dda59e51f5b0deb3c16669c7e9ac2bc72d9de21e2c41e0033edecc20d653f024',
        ]
    ],
    ResponseFactoryInterface::class => DI\get(ResponseFactory::class),
    Session::class => function (ContainerInterface $container) {
        $settings = $container->get('config')['session'];
        if (PHP_SAPI === 'cli') {
            return new Session(new MockArraySessionStorage());
        } else {
            return new Session(new NativeSessionStorage($settings, new NativeFileSessionHandler()));
        }
    },
    SessionInterface::class => DI\get(Session::class),
    ClientRepositoryInterface::class => DI\get(\App\OAuth\Repository\ClientRepository::class),
    AccessTokenRepositoryInterface::class => DI\get(AccessTokenRepository::class),
    ScopeRepositoryInterface::class => DI\get(ScopeRepository::class),
    AuthorizationServer::class => function (ContainerInterface $container) {
        $clientRepository = $container->get(ClientRepositoryInterface::class);
        $accessTokenRepository = $container->get(AccessTokenRepositoryInterface::class);
        $scopeRepository = $container->get(ScopeRepositoryInterface::class);
        $privateKey = 'file://' . $container->get('config')['oauth']['private_key_path'];

        $server = new AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $privateKey,
            Key::loadFromAsciiSafeString($container->get('config')['oauth']['encryption_key'])
        );

        $server->enableGrantType(
            new ImplicitGrant(new DateInterval('PT1H')),
            new DateInterval('PT1H')
        );

        return $server;
    },
    Configuration::class => function (ContainerInterface $container) {
        $signingKey = $container->get('config')['auth']['signing_key'];
        $verificationKey = $container->get('config')['auth']['verification_key'];

        $config =  Configuration::forAsymmetricSigner(
            new \Lcobucci\JWT\Signer\Hmac\Sha256(),
            LocalFileReference::file($signingKey),
            InMemory::base64Encoded($verificationKey),
        );

        $config->setValidationConstraints();
        return $config;
    }
];
