<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Repository\DVDRepository;

use Jayrods\ProductInventory\Entity\DVD;
use Jayrods\ProductInventory\Repository\DVDRepository\DVDRepository;
use Jayrods\ProductInventory\Repository\Repository;
use PDO;

class MysqlDVDRepository extends Repository implements DVDRepository
{
    /**
     * Persist a DVD on database.
     *
     * @param DVD $dvd Instance of DVD.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function save(DVD $dvd): bool
    {
        return $this->create($dvd);
    }

    /**
     * Persist a new DVD on database.
     *
     * @param DVD $dvd Instance of DVD.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    private function create(DVD $dvd): bool
    {
        $query = "CALL insert_into_products_dvds_tables(:sku, :name, :price, :type, :size)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(":sku", $dvd->sku(), PDO::PARAM_STR);
        $stmt->bindValue(":name", $dvd->name(), PDO::PARAM_STR);
        $stmt->bindValue(":price", $dvd->price(), PDO::PARAM_INT);
        $stmt->bindValue(":type", $dvd->type(), PDO::PARAM_STR);
        $stmt->bindValue(":size", $dvd->size(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Retrieve all DVDs from database.
     *
     * @return DVD[] Array of DVD objects.
     */
    public function all(): array
    {
        $query = "SELECT
            products.sku, products.name, products.price, dvds.size
            FROM dvds
            INNER JOIN products ON products.sku = dvds.sku";

        $stmt = $this->conn->query($query);

        return $stmt->fetchAll(
            PDO::FETCH_FUNC,
            function ($sku, $name, $price, $size) {
                return new DVD($sku, $name, $price, $size);
            }
        );
    }
}
