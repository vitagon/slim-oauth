<?php

require __DIR__ . '/../vendor/autoload.php';

use DI\Container;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

/** @var Container $container */
$container = require __DIR__ . '/container.php';

return ConsoleRunner::createHelperSet($container->get(EntityManagerInterface::class));
