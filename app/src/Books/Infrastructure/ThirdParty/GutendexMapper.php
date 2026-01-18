<?php
declare(strict_types=1);

namespace Library\Books\Infrastructure\ThirdParty;

use Library\Books\Domain\Model\BookModel;
use Library\Books\Domain\Model\PersonModel;
use Library\Books\Domain\VO\BookId;
use Library\Books\Domain\VO\BookTitle;
use Library\Books\Domain\VO\PersonBirthYear;
use Library\Books\Domain\VO\PersonDeathYear;
use Library\Books\Domain\VO\PersonName;
use Library\Books\Domain\VO\Subject;
use Library\Books\Domain\VO\Subjects;

final class GutendexMapper
{
    /**
     * @param array<string,mixed> $item  Un elemento de "results" de Gutendex
     */
    public function toBookModel(array $item): BookModel
    {
        $id = new BookId((int) ($item['id'] ?? 0));
        $title = new BookTitle((string) ($item['title'] ?? ''));

        $subjects = $this->mapSubjects($item['subjects'] ?? []);
        $authors  = $this->mapAuthors($item['authors'] ?? []);

        return new BookModel(
            $id,
            $title,
            $subjects,
            $authors
        );
    }

    /**
     * @param mixed $subjectsRaw
     */
    private function mapSubjects(mixed $subjectsRaw): Subjects
    {
        $items = [];

        if (is_array($subjectsRaw)) {
            foreach ($subjectsRaw as $s) {
                if (!is_string($s)) {
                    continue;
                }
                $items[] = new Subject($s);
            }
        }

        return new Subjects($items);
    }

    /**
     * @param mixed $authorsRaw
     * @return PersonModel[]
     */
    private function mapAuthors(mixed $authorsRaw): array
    {
        $authors = [];
        
        if (!is_array($authorsRaw)) {
            return $authors;
        }

        foreach ($authorsRaw as $a) {
            if (!is_array($a)) {
                continue;
            }

            $nameRaw = $a['name'] ?? '';
            if (!is_string($nameRaw)) {
                $nameRaw = '';
            }

            $birthRaw = $a['birth_year'] ?? null;
            $deathRaw = $a['death_year'] ?? null;

            $birthYear = is_int($birthRaw) ? new PersonBirthYear($birthRaw) : null;
            $deathYear = is_int($deathRaw) ? new PersonDeathYear($deathRaw) : null;

            $authors[] = new PersonModel(
                new PersonName($nameRaw),
                $birthYear,
                $deathYear
            );
        }

        return $authors;
    }
}
