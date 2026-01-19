<?php
declare(strict_types=1);

namespace Library\Tests\Behat\Fake;

use Library\Books\Domain\Model\BookModel;
use Library\Books\Domain\Model\PersonModel;
use Library\Books\Domain\Repository\IBookRepository;
use Library\Books\Domain\VO\BookId;
use Library\Books\Domain\VO\BookTitle;
use Library\Books\Domain\VO\PersonBirthYear;
use Library\Books\Domain\VO\PersonDeathYear;
use Library\Books\Domain\VO\PersonName;
use Library\Books\Domain\VO\Subject;
use Library\Books\Domain\VO\Subjects;

final class FakeBookRepository implements IBookRepository
{
    /** @var array<int, BookModel> */
    private array $books;

    public function __construct()
    {
        $this->books = [
            1 => new BookModel(
                new BookId(1),
                new BookTitle('Test Book'),
                new Subjects([new Subject('Love stories')]),
                [
                    new PersonModel(
                        new PersonName('Jefferson, Thomas'),
                        new PersonBirthYear(1743),
                        new PersonDeathYear(1826),
                    ),
                ]
            ),
        ];
    }

    public function findOneById(int $id): ?BookModel
    {
        return $this->books[$id] ?? null;
    }

    /** @return BookModel[] */
    public function search(string $query): array
    {
        $q = mb_strtolower(trim($query));
        if ($q === '') {
            return [];
        }

        $out = [];
        foreach ($this->books as $book) {
            if (str_contains(mb_strtolower($book->title()), $q)) {
                $out[] = $book;
            }
        }

        return $out;
    }
}
