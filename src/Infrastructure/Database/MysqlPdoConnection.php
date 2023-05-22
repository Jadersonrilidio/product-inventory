<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Infrastructure\Database;

use Jayrods\ProductInventory\Helper\Environment as Env;
use Jayrods\ProductInventory\Infrastructure\Database\PdoConnectionSingleton;
use PDO;

class MysqlPdoConnection extends PdoConnectionSingleton
{
    /**
     * Create a MySQL PDO connection.
     * 
     * @return void
     */
    protected function connect(): void
    {
        $dsn = $this->mountDsn();

        $user = Env::env('DB_USER', '');
        $password = Env::env('DB_PASSWORD', '');

        self::$connection = new PDO($dsn, $user, $password);
    }

    /**
     * Mount DSN string according to ENVIRONMENT variables.
     * 
     * @return string Mounted DSN.
     */
    private function mountDsn(): string
    {
        $driver = Env::env('DB_DRIVER', 'mysql');
        $host = Env::env('DB_HOST', 'localhost');
        $port = Env::env('DB_PORT', null);
        $dbname = Env::env('DB_NAME', null);

        $dsn = "$driver:";

        if (!is_null($host)) {
            $dsn .= "host={$host};";
        }

        if (!is_null($port)) {
            $dsn .= "port={$port};";
        }

        if (!is_null($dbname)) {
            $dsn .= "dbname={$dbname};";
        }

        return rtrim($dsn, ';');
    }
}
