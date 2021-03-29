<?php

require __DIR__ . '/../vendor/autoload.php';

use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;

$config = new PhpFile('migrations.php'); // Or use one of the Doctrine\Migrations\Configuration\Configuration\* loaders

$paths = [__DIR__ . '/../src/Models'];
$isDevMode = true;

$ORMconfig = Setup::createAnnotationMetadataConfiguration(
    $paths,
    $isDevMode,
    null,
    null,
    false
);
$entityManager = EntityManager::create([
    'driver' => 'pdo_mysql',
    'host' => 'mysql',
    'port' => 3306,
    'dbname' => 'company',
    'user' => 'root',
    'password' => 'root',
    'charset' => 'utf8'
], $ORMconfig);

return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));
