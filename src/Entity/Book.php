<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Entity;

use Jayrods\ProductInventory\Entity\Product;

class Book extends Product
{
    /**
     * Book weight in Kg.
     */
    private int $weight;

    /**
     * Class constructor.
     *
     * @param string $sku
     * @param string $name
     * @param int    $price
     * @param int    $weight
     */
    public function __construct(string $sku, string $name, int $price, int $weight)
    {
        parent::__construct($sku, $name, $price);

        $this->type = 'Book';
        $this->weight = $weight;
    }

    /**
     * Return Book weight in KG.
     *
     * @return int
     */
    public function weight(): int
    {
        return $this->weight;
    }

    /**
     * Return formated Book weight in KG.
     *
     * @return string
     */
    public function formatedWeight(): string
    {
        return (string) $this->weight . "Kg";
    }

    /**
     * Return formated specific attributes.
     *
     * @return string
     */
    public function formatedSpecificAttributes(): string
    {
        return 'Weight: ' . $this->formatedWeight();
    }
}
