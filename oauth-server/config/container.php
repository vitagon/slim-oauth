<?php

declare(strict_types=1);

use DI\ContainerBuilder;

define('APP_ROOT', realpath(__DIR__ . '/../'));

$builder = new ContainerBuilder();

$builder->addDefinitions(require __DIR__ . '/dependencies.php');

return $builder->build();
