<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Repository\FurnitureRepository;

use Jayrods\ProductInventory\Entity\Furniture;
use Jayrods\ProductInventory\Repository\FurnitureRepository\FurnitureRepository;
use Jayrods\ProductInventory\Repository\Repository;
use PDO;

class MysqlFurnitureRepository extends Repository implements FurnitureRepository
{
    /**
     * Persist a furniture on database.
     *
     * @param Furniture $furniture Instance of Furniture.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function save(Furniture $furniture): bool
    {
        return $this->create($furniture);
    }

    /**
     * Persist a new furniture on database.
     *
     * @param Furniture $furniture Instance of Furniture.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    private function create(Furniture $furniture): bool
    {
        $query = "CALL insert_into_products_furniture_tables(:sku, :name, :price, :type, :height, :width, :length)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(":sku", $furniture->sku(), PDO::PARAM_STR);
        $stmt->bindValue(":name", $furniture->name(), PDO::PARAM_STR);
        $stmt->bindValue(":price", $furniture->price(), PDO::PARAM_INT);
        $stmt->bindValue(":type", $furniture->type(), PDO::PARAM_STR);
        $stmt->bindValue(":height", $furniture->height(), PDO::PARAM_INT);
        $stmt->bindValue(":width", $furniture->width(), PDO::PARAM_INT);
        $stmt->bindValue(":length", $furniture->length(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Retrieve all furniture from database.
     *
     * @return Furniture[] Array of Furniture objects.
     */
    public function all(): array
    {
        $query = "SELECT
            products.sku, products.name, products.price, furniture.height, furniture.width, furniture.length
            FROM furniture
            INNER JOIN products ON products.sku = furniture.sku";

        $stmt = $this->conn->query($query);

        return $stmt->fetchAll(
            PDO::FETCH_FUNC,
            function ($sku, $name, $price, $height, $width, $length) {
                return new Furniture($sku, $name, $price, $height, $width, $length);
            }
        );
    }
}
