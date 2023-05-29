<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Repository\FurnitureRepository;

use Jayrods\ProductInventory\Entity\Furniture;

interface FurnitureRepository
{
    /**
     * Persist a furniture on database.
     *
     * @param Furniture $furniture Instance of Furniture.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function save(Furniture $furniture): bool;

    /**
     * Retrieve all furnitures from database.
     *
     * @return Furniture[] Array of Furniture objects.
     */
    public function all(): array;
}
