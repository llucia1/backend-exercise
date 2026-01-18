<?php
declare(strict_types=1);

namespace Library\Books\Infrastructure\ThirdParty;

use Library\Books\Domain\Model\BookModel;
use Library\Books\Domain\Repository\IBookRepository;

final class GutendexBookRepository implements IBookRepository
{
    public function __construct(
        private readonly GutendexClient $client,
        private readonly GutendexMapper $mapper
    ) {}

    public function findOneById(int $id): ?BookModel
    {
        $data = $this->client->getBooksByIds([$id]);

        if (!is_array($data) || !isset($data['results']) || !is_array($data['results'])) {
            return null;
        }

        if (count($data['results']) === 0) {
            return null;
        }

        $first = $data['results'][0];

        if (!is_array($first)) {
            return null;
        }

        return $this->mapper->toBookModel($first);
    }

    /**
     * @return BookModel[]
     */
    public function search(string $query): array
    {
        $data = $this->client->searchBooks($query);

        if (!is_array($data) || !isset($data['results']) || !is_array($data['results'])) {
            return [];
        }

        $books = [];

        foreach ($data['results'] as $item) {
            if (!is_array($item)) {
                continue;
            }

            $books[] = $this->mapper->toBookModel($item);
        }

        return $books;
    }
}
