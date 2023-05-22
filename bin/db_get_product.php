#!usr/bin/env php
<?php

declare(strict_types=1);

use Jayrods\ProductInventory\Infrastructure\Database\MysqlPdoConnection;
use Jayrods\ProductInventory\Repository\MysqlRepositoryFactory;
use Jayrods\ProductInventory\Repository\ProductRepository\MysqlProductRepository;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

// all, DVD, Book, Furniture ...
$type = $argv[1] ?? 'all';

// list, print, dump
$mode = $argv[2] ?? 'list';

$mysqlPdoConnection = new MysqlPdoConnection;

$connection = $mysqlPdoConnection->getConnection();
$connection->query("USE product_inventory_db;");

$products = [];

if ($type === 'all') {
    $productRepository = new MysqlProductRepository($connection, new MysqlRepositoryFactory());
    $products = $productRepository->all();
} else {
    $repositoryFactory = new MysqlRepositoryFactory();
    $genericProductsRepository = $repositoryFactory->create($type, $connection);
    $products = $genericProductsRepository->all();
}

switch ($mode) {
    case 'dump':
        var_dump($products);
        break;
    case 'print':
        print_r($products);
        break;
    default:
        foreach ($products as $product) {
            echo $product->sku() . ' - ' . $product->name() . ' - ' . $product->formatedPrice() . ' - ' . $product->formatedSpecificAttributes() . PHP_EOL;
        };
}
