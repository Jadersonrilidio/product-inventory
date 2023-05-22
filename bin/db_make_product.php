#!usr/bin/env php
<?php

declare(strict_types=1);

use Jayrods\ProductInventory\Infrastructure\Database\MysqlPdoConnection;
use Jayrods\ProductInventory\Repository\MysqlRepositoryFactory;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

$type = $argv[1];

$class = "Jayrods\\ProductInventory\\Entity\\" . $type;

$args = array(
    $argv[2],
    $argv[3],
    (int) $argv[4],
    (int) $argv[5],
    isset($argv[6]) ? (int) $argv[6] : null,
    isset($argv[7]) ? (int) $argv[7] : null,
);

$product = new $class(...$args);

$mysqlPdoConnection = new MysqlPdoConnection;

$connection = $mysqlPdoConnection->getConnection();
$connection->query("USE product_inventory_db;");

$repositoryFactory = new MysqlRepositoryFactory();

$genericRepository = $repositoryFactory->create($type, $connection);

$genericRepository->save($product);

echo "Query executed with success." . PHP_EOL;

