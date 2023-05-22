#!usr/bin/env php
<?php

declare(strict_types=1);

use Jayrods\ProductInventory\Entity\Book;
use Jayrods\ProductInventory\Entity\DVD;
use Jayrods\ProductInventory\Entity\Furniture;
use Jayrods\ProductInventory\Infrastructure\Database\MysqlPdoConnection;
use Jayrods\ProductInventory\Repository\MysqlRepositoryFactory;
use Jayrods\ProductInventory\Repository\ProductRepository\MysqlProductRepository;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

$productsArray = array(
    new DVD("PID20001", "Blue Ray", 7, 4000),
    new DVD("PID20002", "CD", 2, 700),
    new Book("PIB20001", "1987", 35, 1),
    new Book("PIB20002", "Cauculum II", 157, 2),
    new Furniture("PIF20001", "Chair", 120, 90, 45, 45),
    new Furniture("PIF20002", "Armchair", 450, 90, 55, 45),
    new Furniture("PIF20003", "Couch", 1500, 75, 90, 300),
);

$mysqlPdoConnection = new MysqlPdoConnection;

$connection = $mysqlPdoConnection->getConnection();
$connection->query("USE product_inventory_db;");

$repositoryFactory = new MysqlRepositoryFactory();

$productRepository = new MysqlProductRepository($connection, $repositoryFactory);

foreach ($productsArray as $product) {
    $productRepository->save($product);
}

echo "Query executed with success." . PHP_EOL;

