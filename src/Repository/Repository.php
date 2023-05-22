<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Repository;

use PDO;

abstract class Repository
{
    /**
     * PDO connection instance.
     */
    protected PDO $conn;

    /**
     * Class constructor.
     * 
     * @param PDO $conn PDO connection instance.
     */
    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }
}
