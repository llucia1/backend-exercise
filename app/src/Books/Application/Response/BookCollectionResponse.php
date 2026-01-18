<?php
declare(strict_types=1);
namespace Library\Books\Application\Response;

final readonly class BookCollectionResponse
{
    private array $books;

    public function __construct(BookResponse ...$books)
    {
        $this->books = $books;
    }
    public static function getBooksModel(array $books): BookCollectionResponse
    {
        return new self(
            ...array_map(fn($result) => BookResponse::byModel($result), $books)
        ); 
    }

    public function get(): array
    {
        return $this->books;
    }
}