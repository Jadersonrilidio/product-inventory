<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Repository;

use PDO;

class MysqlRepositoryFactory
{
    /**
     * Instantiate a MysqlRepository according to given entityName.
     * 
     * @param string $entityName Name of the Entity of interest.
     * @param PDO $conn PDO connection with database.
     */
    public function create(string $entityName, PDO $conn)
    {
        $repositoryClassname = __NAMESPACE__ . '\\' . "{$entityName}Repository\\Mysql{$entityName}Repository";

        return new $repositoryClassname($conn);
    }
}
