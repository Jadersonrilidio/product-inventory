<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Repository\ProductRepository;

use Jayrods\ProductInventory\Entity\Product;
use Jayrods\ProductInventory\Entity\ProductFactory;
use Jayrods\ProductInventory\Repository\Repository;
use Jayrods\ProductInventory\Repository\MysqlRepositoryFactory;
use Jayrods\ProductInventory\Repository\ProductRepository\ProductRepository;
use PDO;

class MysqlProductRepository extends Repository implements ProductRepository
{
    /**
     * MysqlRepositoryFactory instance.
     */
    private MysqlRepositoryFactory $repositoryFactory;

    /**
     * Class constructor.
     *
     * @param PDO $conn PDO connection instance.
     * @param MysqliRepositoryFacotry $repositoryFactory MysqlRepositoryFactory instance.
     */
    public function __construct(PDO $conn, MysqlRepositoryFactory $repositoryFactory)
    {
        parent::__construct($conn);

        $this->repositoryFactory = $repositoryFactory;
    }

    /**
     * Persist a product on database.
     *
     * @param Product $product Instance of Product.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function save(Product $product): bool
    {
        return $this->skuExists($product->sku()) ? false : $this->create($product);
    }

    /**
     * Persist a new product on database.
     *
     * @param Product $product Instance of Product.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    private function create(Product $product): bool
    {
        $specificRepository = $this->repositoryFactory->create(
            $product->type(),
            $this->conn
        );

        return $specificRepository->save($product);
    }

    /**
     * Delete a product from database by SKU.
     *
     * @param string $skuList Product SKU list.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function removeManyBySku(string ...$skuList): bool
    {
        $query = "DELETE FROM products WHERE sku = :sku";

        $this->conn->beginTransaction();

        $stmt = $this->conn->prepare($query);

        foreach ($skuList as $sku) {
            $stmt->bindValue(":sku", $sku, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                $this->conn->rollBack();
                return false;
            }
        }

        return $this->conn->commit();
    }

    /**
     * Retrieve all products from database.
     *
     * @return Product[] Array of Product objects.
     */
    public function all(): array
    {
        $stmt = $this->conn->query(
            "SELECT
                products.sku, products.name, products.price, products.type,
                dvds.size,
                books.weight,
                furniture.height, furniture.width, furniture.length
            FROM products
                LEFT JOIN dvds ON dvds.sku = products.sku
                LEFT JOIN books ON books.sku = products.sku
                LEFT JOIN furniture ON furniture.sku = products.sku
            ORDER BY products.sku ASC;"
        );

        $products = $stmt->fetchAll(
            PDO::FETCH_FUNC,
            function (...$productData) {
                return ProductFactory::create($productData);
            }
        );

        return $products;
    }

    /**
     * Retrieve all products from database.
     *
     * @return Product[] Array of Product objects.
     */
    public function improvedAll(): array
    {
        $products = [];

        foreach ($this->getEnumTypes() as $type) {
            $specificRepository = $this->repositoryFactory->create($type, $this->conn);
            array_push($products, $specificRepository->all());
        }

        usort($products, fn ($prev, $next) => strcasecmp($prev->sku(), $next->sku()));

        return $products;
    }

    /**
     * Retrieve all products SKU from database.
     *
     * @return string[] Array of producs SKU.
     */
    public function skuList(): array
    {
        $stmt = $this->conn->query("SELECT sku FROM products;");

        $skuList = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $skuList;
    }

    /**
     * Check whether a SKU value exists on database.
     *
     * @param string $sku Product SKU to be search.
     *
     * @return bool Return TRUE if SKU already exists or FALSE otherwise.
     */
    public function skuExists(string $sku): bool
    {
        $query = "SELECT sku FROM products WHERE sku = :sku";

        $stmt = $this->conn->prepare($query);

        $stmt->execute(
            array(
                ':sku' => $sku
            )
        );

        return empty($stmt->fetchAll()) ? false : true;
    }

    /**
     * Return array containing the Enum types from the Field 'type'.
     *
     * @return string[] Array of product types.
     */
    public function getEnumTypes(): array
    {
        $query = "SHOW COLUMNS FROM products WHERE Field = 'type'";

        $stmt = $this->conn->query($query);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $types = str_replace(["enum(", ")", "'"], '', $result['Type']);

        return explode(',', $types);
    }

    //TODO: Not used but usefull methods.

    // /**
    //  * Delete a product from database.
    //  *
    //  * @param Product $product Instance of Product.
    //  *
    //  * @return bool TRUE on success or FALSE on failure.
    //  */
    // public function remove(Product $product): bool
    // {
    //     $query = "DELETE FROM products WHERE sku = :sku";

    //     $stmt = $this->conn->prepare($query);

    //     $stmt->bindValue(":sku", $product->sku(), PDO::PARAM_STR);

    //     return $stmt->execute();
    // }

    // /**
    //  * Find and retrieve a product by its SKU.
    //  *
    //  * @param string $sku Product SKU to be found.
    //  *
    //  * @return Product|false Product object on success or FALSE on failure.
    //  */
    // public function find(string $sku): Product|false
    // {
    //     $query = "SELECT sku, type FROM products WHERE sku = :sku";

    //     $stmt = $this->conn->prepare($query);

    //     $stmt->bindValue(":sku", $sku, PDO::PARAM_STR);

    //     if (!$stmt->execute()) {
    //         return false;
    //     }

    //     $partialProductData = $stmt->fetch(PDO::FETCH_ASSOC);

    //     $genericRepository = $this->repositoryFactory->create($partialProductData['type'], $this->conn);

    //     return $genericRepository->find($partialProductData['sku']);
    // }
}
