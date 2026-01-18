<?php
declare(strict_types=1);

namespace Library\Books\Application\Response;

use Library\Books\Domain\Model\BookModel;
use Library\Books\Domain\Model\PersonModel;

final class BookResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        /** @var string[] */
        public readonly array $subjects,
        /** @var array<int, array{name:string, birth_year:?int, death_year:?int}> */
        public readonly array $authors,
    ) {}

    public static function byModel(BookModel $book): self
    {
        return new self(
            $book->id(),
            $book->title(),
            $book->subjects(),
            $book->authors()
        );
    }
    public function id(): int
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    /**
     * @return string[]
     */
    public function subjects(): array
    {
        return $this->subjects;
    }

    /**
     * @return array<int, array{name:string, birth_year:?int, death_year:?int}>
     */
    public function authors(): array
    {
        return $this->authors;
    }

    /**
     * @param PersonModel[] $authors
     * @return array<int, array{name:string, birth_year:?int, death_year:?int}>
     */
    private static function mapAuthors(array $authors): array
    {
        return array_map(
            static fn (PersonModel $author) => [
                'name'        => $author->name(),
                'birth_year'  => $author->birthYear(),
                'death_year'  => $author->deathYear(),
            ],
            $authors
        );
    }
}