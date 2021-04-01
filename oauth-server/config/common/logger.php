<?php

declare(strict_types=1);

use App\Http\Logger\CustomLineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    'config' => [
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : realpath(__DIR__ . '/../../logs/app.log'),
            'level' => Logger::DEBUG,
        ],
    ],

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