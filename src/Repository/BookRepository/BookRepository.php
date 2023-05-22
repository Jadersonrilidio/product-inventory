<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Repository\BookRepository;

use Jayrods\ProductInventory\Entity\Book;

interface BookRepository
{
    /**
     * Persist a book on database.
     * 
     * @param Book $book Instance of Book.
     * 
     * @return bool TRUE on success or FALSE on failure.
     */
    public function save(Book $book): bool;

    /**
     * Retrieve all books from database.
     * 
     * @return Book[] Array of Book objects.
     */
    public function all(): array;
}
