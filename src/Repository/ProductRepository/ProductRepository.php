<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Repository\ProductRepository;

use Jayrods\ProductInventory\Entity\Product;

interface ProductRepository
{
    /**
     * Persist a product on database.
     * 
     * @param Product $product Instance of Product.
     * 
     * @return bool TRUE on success or FALSE on failure.
     */
    public function save(Product $product): bool;

    /**
     * Delete product(s) from database by SKU.
     * 
     * @param string $skuList Product SKU list.
     * 
     * @return bool TRUE on success or FALSE on failure.
     */
    public function removeManyBySku(string ...$skuList): bool;

    /**
     * Retrieve all products from database.
     * 
     * @return Product[] Array of Product objects.
     */
    public function all(): array;

    /**
     * Retrieve all products SKU from database.
     * 
     * @return string Array of producs SKU.
     */
    public function skuList(): array;

    /**
     * Check whether a SKU value exists on database.
     * 
     * @param string $sku Product SKU to be search.
     * 
     * @return bool Return TRUE if SKU already exists or FALSE otherwise.
     */
    public function skuExists(string $sku): bool;

    /**
     * Return array containing the Enum types from the Field 'type'.
     * 
     * @return array
     */
    public function getEnumTypes(): array;

    //TODO: Not used but usefull methods.

    // /**
    //  * Delete a product from database.
    //  * 
    //  * @param Product $product Instance of Product.
    //  * 
    //  * @return bool TRUE on success or FALSE on failure.
    //  */
    // public function remove(Product $product): bool;

    // /**
    //  * Find and retrieve a product by its SKU.
    //  * 
    //  * @param string $sku Product SKU to be found.
    //  * 
    //  * @return Product|false Product object on success or FALSE on failure.
    //  */
    // public function find(string $sku): Product|false;
}
