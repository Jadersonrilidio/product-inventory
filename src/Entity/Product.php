<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Entity;

use JsonSerializable;

abstract class Product implements JsonSerializable
{
    /**
     * Product SKU (Stock Keeping Unit).
     */
    protected string $sku;

    /**
     * Product name.
     */
    protected string $name;

    /**
     * Product prize in $.
     */
    protected int $price;

    /**
     * Product type. Represented by Enum: 'DVD', 'Book', 'Furniture'.
     */
    protected string $type;

    /**
     * Class constructor.
     * 
     * @param string $sku
     * @param string $name
     * @param int $price
     */
    public function __construct(string $sku, string $name, int $price)
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * Return product SKU (Stock Keeping Unit).
     * 
     * @return string
     */
    public function sku(): string
    {
        return $this->sku;
    }

    /**
     * Return product name.
     * 
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Return product price (in cents of $).
     * 
     * @return int
     */
    public function price(): int
    {
        return $this->price;
    }

    /**
     * Return product type.
     * 
     * Enum representation on database with values: DVD, Book, Furniture.
     * 
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Return formated product price (Ex: 120 $).
     * 
     * @return string
     */
    public function formatedPrice(): string
    {
        return (string) $this->price . " $";
    }

    /**
     * Return formated specific attributes.
     * 
     * @return string
     */
    abstract public function formatedSpecificAttributes(): string;

    /**
     * Specify data which should be serialized to JSON.
     * 
     * @return mixed data which can be serialized by json_encode, which is a value of any type other than a resource.
     */
    public function jsonSerialize(): mixed
    {
        return array(
            'sku' => $this->sku,
            'name' => $this->name,
            'price' => $this->formatedPrice(),
            'specific-attribute' => $this->formatedSpecificAttributes()
        );
    }

    /**
     *  Is run when writing data to inaccessible (protected or private) or non-existing properties.
     * 
     * @param string $name
     * @param mixed $value
     * 
     * @return void
     */
    public function __set($name, $value): void
    {
        // The __set() magic method would be usefull in case of applying the PDO::FETCH_CLASS mode during PDOStatement::fetchAll() method.

        // if (property_exists($this, $name) and !isset($this->$name)) {
        //     $this->$name = $value;
        // }
    }
}
