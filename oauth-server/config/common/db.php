<?php

declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;

return [
    'config' => [
        'db' => [
            'displayErrorDetails' => true,
            'determineRouteBeforeAppMiddleware' => false,

            'doctrine' => [
                // if true, metadata caching is forcefully disabled
                'dev_mode' => true,

                // path where the compiled metadata info will be cached
                // make sure the path exists and it is writable
                'cache_dir' => APP_ROOT . '/var/cache/doctrine',

                // you should add any other path containing annotated entity classes
                'metadata_dirs' => [
                    SRC_ROOT . '/Model',
                ],

                'connection' => [
                    'driver' => 'pdo_mysql',
                    'host' => 'mysql',
                    'port' => 3306,
                    'dbname' => 'company',
                    'user' => 'root',
                    'password' => 'root',
                    'charset' => 'utf8',
                ],
            ],
        ],
    ],

    EntityManagerInterface::class => function (ContainerInterface $container) {
        $settings = $container->get('config')['db']['doctrine'];

        $settings['cache_dir'] ? new FilesystemCache($settings['cache_dir']) : new ArrayCache();
        $config = Setup::createAnnotationMetadataConfiguration(
            $container->get('config')['db']['doctrine']['metadata_dirs'],
            $container->get('config')['db']['doctrine']['dev_mode'],
            null,
            null,
            false,
        );

        $config->setNamingStrategy(new UnderscoreNamingStrategy());

        return EntityManager::create(
            $container->get('config')['db']['doctrine']['connection'],
            $config
        );
    },
];
