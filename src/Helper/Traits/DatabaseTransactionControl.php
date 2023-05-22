<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Helper\Traits;

/**
 * Make database transaction control functions available.
 * 
 * @method public beginTransaction() Initiates a transaction.
 * @method public commit()  Commits a transaction.
 * @method public rollBack() Rolls back a transaction.
 */
trait DatabaseTransactionControl
{
    /**
     * Initiates a transaction.
     * 
     * @see https://php.net/manual/en/pdo.begintransaction.php
     * 
     * @throws PDOException If there is already a transaction started or the driver does not support transactions Note: An exception is raised even when the PDO::ATTR_ERRMODE attribute is not PDO::ERRMODE_EXCEPTION.
     * 
     * @return bool TRUE on success or FALSE on failure.
     */
    public function beginTransaction(): bool
    {
        return $this->conn->beginTransaction();
    }

    /**
     *  Commits a transaction.
     * 
     * @see https://php.net/manual/en/pdo.commit.php
     * 
     * @throws PDOException if there is no active transaction.
     * 
     * @return bool TRUE on success or FALSE on failure.
     */
    public function commit(): bool
    {
        return $this->conn->commit();
    }

    /**
     * Rolls back a transaction.
     * 
     * @see https://php.net/manual/en/pdo.rollback.php
     * 
     * @throws PDOException if there is no active transaction.
     * 
     * @return bool TRUE on success or FALSE on failure.
     */
    public function rollBack(): bool
    {
        return $this->conn->rollback();
    }
}
