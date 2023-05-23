<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Entity;

use Jayrods\ProductInventory\Entity\Product;

class ProductFactory
{
    /**
     * Create an instance of Product according to product type.
     * 
     * @param mixed $productData Array with product attributes.
     * 
     * @return Product Concrete Product object.
     */
    public static function create(array $productData): Product
    {
        $class = __NAMESPACE__ . '\\' . $productData[3];
        unset($productData[3]);

        foreach ($productData as $key => $value) {
            if (is_null($value)) {
                unset($productData[$key]);
            }
        }

        return new $class(...$productData);
    }
}
