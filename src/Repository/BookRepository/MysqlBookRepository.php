<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Repository\BookRepository;

use Jayrods\ProductInventory\Entity\Book;
use Jayrods\ProductInventory\Repository\BookRepository\BookRepository;
use Jayrods\ProductInventory\Repository\Repository;
use PDO;

class MysqlBookRepository extends Repository implements BookRepository
{
    /**
     * Persist a book on database.
     *
     * @param Book $book Instance of Book.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    public function save(Book $book): bool
    {
        return $this->create($book);
    }

    /**
     * Persist a new book on database.
     *
     * @param Book $book Instance of Book.
     *
     * @return bool TRUE on success or FALSE on failure.
     */
    private function create(Book $book): bool
    {
        $query = "CALL insert_into_products_books_tables(:sku, :name, :price, :type, :weight)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(":sku", $book->sku(), PDO::PARAM_STR);
        $stmt->bindValue(":name", $book->name(), PDO::PARAM_STR);
        $stmt->bindValue(":price", $book->price(), PDO::PARAM_INT);
        $stmt->bindValue(":type", $book->type(), PDO::PARAM_STR);
        $stmt->bindValue(":weight", $book->weight(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Retrieve all books from database.
     *
     * @return Book[] Array of Book objects.
     */
    public function all(): array
    {
        $query = "SELECT
            products.sku, products.name, products.price, books.weight
            FROM books
            INNER JOIN products ON products.sku = books.sku";

        $stmt = $this->conn->query($query);

        return $stmt->fetchAll(
            PDO::FETCH_FUNC,
            function ($sku, $name, $price, $weight) {
                return new Book($sku, $name, $price, $weight);
            }
        );
    }
}
