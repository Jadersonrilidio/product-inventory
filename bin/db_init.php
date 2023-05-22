#!usr/bin/env php
<?php

declare(strict_types=1);

use Jayrods\ProductInventory\Infrastructure\Database\MysqlPdoConnection;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

$typeEnum = ['DVD', 'Book', 'Furniture'];

$queries = array(
    'Create_Database' => "CREATE SCHEMA IF NOT EXISTS product_inventory_db
            DEFAULT CHARACTER SET = utf8mb4
            DEFAULT COLLATE = utf8mb4_general_ci;",

    'Use_Database' => "USE product_inventory_db;",

    'Create_Table_Products' => "CREATE TABLE IF NOT EXISTS products (
            sku CHAR(32) UNIQUE NOT NULL PRIMARY KEY,
            name VARCHAR(128) NOT NULL,
            price INT NOT NULL,
            type ENUM(".typeEnum($typeEnum).") NOT NULL
        );",

    'Create_Table_DVD' => "CREATE TABLE IF NOT EXISTS dvds (
            sku CHAR(32) UNIQUE NOT NULL PRIMARY KEY,
            size INT UNSIGNED NOT NULL,
            CONSTRAINT FK_sku_dvds FOREIGN KEY (sku) REFERENCES products(sku)
                ON DELETE CASCADE
                ON UPDATE NO ACTION
        );",

    'Create_Table_Book' => "CREATE TABLE IF NOT EXISTS books (
            sku CHAR(32) UNIQUE NOT NULL PRIMARY KEY,
            weight INT NOT NULL,
            CONSTRAINT FK_sku_books FOREIGN KEY (sku) REFERENCES products(sku)
                ON DELETE CASCADE
                ON UPDATE NO ACTION
        );",

    'Create_Table_Furniture' => "CREATE TABLE IF NOT EXISTS furniture (
            sku CHAR(32) UNIQUE NOT NULL PRIMARY KEY,
            height INT NOT NULL,
            width INT NOT NULL,
            length INT NOT NULL,
            CONSTRAINT FK_sku_furniture FOREIGN KEY (sku) REFERENCES products(sku)
                ON DELETE CASCADE
                ON UPDATE NO ACTION
        );"
);

$mysqlPdoConnection = new MysqlPdoConnection;

$connection = $mysqlPdoConnection->getConnection();

foreach ($queries as $key => $query) {
    $connection->query($query);
    echo "Query '{$key}' executed with success." . PHP_EOL;
}

exit("Queries executed with success." . PHP_EOL);

//TODO: FUNCTIONS

/**
 * Format an array of Enum types into a string to fit a SQL query.
 * 
 * @param array $typeEnum Array with Enum types.
 * 
 * @return string Formated Enum types.
 */
function typeEnum(array $typeEnum): string
{
    $quotedTypeEnum = array_map(fn ($type) => "'{$type}'", $typeEnum);

    return implode(',', $quotedTypeEnum);
}
