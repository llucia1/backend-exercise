<?php
declare(strict_types=1);

namespace Library\Books\Domain\Model;

use Library\Books\Domain\VO\BookId;
use Library\Books\Domain\VO\BookTitle;
use Library\Books\Domain\VO\Subjects;

final class BookModel
{
    private BookId $id;
    private BookTitle $title;
    private Subjects $subjects;

    /** @var PersonModel[] */
    private array $authors;

    /**
     * @param PersonModel[] $authors
     */
    public function __construct(
        BookId $id,
        BookTitle $title,
        Subjects $subjects,
        array $authors
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->subjects = $subjects;
        $this->authors = $authors;
    }

    public function id(): int
    {
        return $this->id->value();
    }

    public function title(): string
    {
        return $this->title->value();
    }

    /**
     * @return string[]
     */
    public function subjects(): array
    {
        return $this->subjects->toStrings();
    }

    /**
     * @return PersonModel[]
     */
    public function authors(): array
    {
        return $this->authors;
    }
}
