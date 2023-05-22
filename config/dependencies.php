<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Jayrods\ProductInventory\Infrastructure\Database\MysqlPdoConnection;
use Jayrods\ProductInventory\Repository\MysqlRepositoryFactory;
use Jayrods\ProductInventory\Repository\ProductRepository\MysqlProductRepository;
use Jayrods\ProductInventory\Repository\ProductRepository\ProductRepository;

$builder = new ContainerBuilder();

$builder->addDefinitions(array(
    PDO::class => function () {
        return (new MysqlPdoConnection())->getConnection();
    },
    ProductRepository::class => function () {
        return new MysqlProductRepository(
            (new MysqlPdoConnection())->getConnection(),
            new MysqlRepositoryFactory()
        );
    }
));

/** @var \Psr\Container\ContainerInterface */
$container = $builder->build();

return $container;
