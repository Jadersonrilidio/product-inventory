<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Infrastructure\Database;

use PDO;

abstract class PdoConnectionSingleton
{
    /**
     * PDO object instance.
     */
    protected static PDO $connection;

    /**
     * Class constructor.
     *
     * If a PDO connection is not set, it create a connection and set PDO attributes.
     */
    public function __construct()
    {
        if (!isset(self::$connection)) {
            $this->connect();
            $this->setAttributes();
        }
    }

    /**
     * Set PDO attributes according to ENVIRONMENT variable.
     *
     * @return void
     */
    private function setAttributes(): void
    {
        $errMode = ENVIRONMENT == 'production' ? PDO::ERRMODE_SILENT : PDO::ERRMODE_EXCEPTION;

        self::$connection->setAttribute(
            PDO::ATTR_ERRMODE,
            $errMode
        );
    }

    /**
     * Create a PDO connection to given database.
     *
     * @return void
     */
    abstract protected function connect(): void;

    /**
     * Return PDO connection.
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return self::$connection;
    }
}
