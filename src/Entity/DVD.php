<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Entity;

use Jayrods\ProductInventory\Entity\Product;

class DVD extends Product
{
    /**
     * DVD's memory size in MB.
     */
    private int $size;

    /**
     * Class constructor.
     *
     * @param string $sku
     * @param string $name
     * @param int    $price
     * @param int    $size
     */
    public function __construct(string $sku, string $name, int $price, int $size)
    {
        parent::__construct($sku, $name, $price);

        $this->type = 'DVD';
        $this->size = $size;
    }

    /**
     * Return DVD's memory size in MB.
     *
     * @return int
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * Return formated DVD's memory size in MB.
     *
     * @return string
     */
    public function formatedSize(): string
    {
        return (string) $this->size . " MB";
    }

    /**
     * Return formated specific attributes.
     *
     * @return string
     */
    public function formatedSpecificAttributes(): string
    {
        return 'Size: ' . $this->formatedSize();
    }
}
