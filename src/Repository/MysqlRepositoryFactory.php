<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Repository;

use PDO;

class MysqlRepositoryFactory
{
    /**
     * Repository classes base namespace.
     */
    private const REPOSITORY_NAMESPACE = "Jayrods\\ProductInventory\\Repository\\";

    /**
     * Instantiate a MysqlRepository according to given entityName.
     * 
     * @param string $entityName Name of the Entity of interest.
     * @param PDO $conn PDO connection with database.
     */
    public function create(string $entityName, PDO $conn)
    {
        $repositoryClassname = self::REPOSITORY_NAMESPACE . "{$entityName}Repository\\Mysql{$entityName}Repository";

        return new $repositoryClassname($conn);
    }
}
