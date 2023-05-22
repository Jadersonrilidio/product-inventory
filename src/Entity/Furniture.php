<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Entity;

use Jayrods\ProductInventory\Entity\Product;

class Furniture extends Product
{
    /**
     * Furniture height in cm.
     */
    private int $height;

    /**
     * Furniture width in cm.
     */
    private int $width;

    /**
     * Furniture length in cm.
     */
    private int $length;

    /**
     * Class constructor.
     * 
     * @param string $sku
     * @param string $name
     * @param int $price
     * @param int $height
     * @param int $width
     * @param int $length
     */
    public function __construct(string $sku, string $name, int $price, int $height, int $width, int $length)
    {
        parent::__construct($sku, $name, $price);

        $this->type = 'Furniture';
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }

    /**
     * Return furniture height in cm.
     * 
     * @return int
     */
    public function height(): int
    {
        return $this->height;
    }

    /**
     * Return furniture width in cm.
     * 
     * @return int
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * Return furniture length in cm.
     * 
     * @return int
     */
    public function length(): int
    {
        return $this->length;
    }

    /**
     * Return formated furniture dimensions in cm.
     * 
     * @return string
     */
    public function dimension(): string
    {
        return (string) $this->height . "x" . (string) $this->width . "x" . (string) $this->length;
    }

    /**
     * Return formated specific attributes.
     * 
     * @return string
     */
    public function formatedSpecificAttributes(): string
    {
        return 'Dimension: ' . $this->dimension();
    }
}
