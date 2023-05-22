<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Repository\DVDRepository;

use Jayrods\ProductInventory\Entity\DVD;

interface DVDRepository
{
    /**
     * Persist a DVD on database.
     * 
     * @param DVD $dvd Instance of DVD.
     * 
     * @return bool TRUE on success or FALSE on failure.
     */
    public function save(DVD $dvd): bool;

    /**
     * Retrieve all DVDs from database.
     * 
     * @return DVD[] Array of DVD objects.
     */
    public function all(): array;
}
