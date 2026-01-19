<?php
declare(strict_types=1);

namespace Library\Books\Infrastructure\Cache;

use Library\Books\Domain\Model\BookModel;
use Library\Books\Domain\Repository\IBookRepository;
use Library\Books\Infrastructure\ThirdParty\GutendexBookRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class CachedBookRepository implements IBookRepository
{
    private const TTL = 300;

    public function __construct(
        private readonly GutendexBookRepository $inner,
        private readonly CacheInterface $cache,
    ) {}

    public function findOneById(int $id): ?BookModel
    {
        return $this->cache->get(
            'books_id_' . $id,
            function (ItemInterface $item) use ($id) {
                $item->expiresAfter(self::TTL);
                return $this->inner->findOneById($id);
            }
        );
    }

    /**
     * @return BookModel[]
     */
    public function search(string $query): array
    {
        return $this->cache->get(
            'books_search_' . md5($query),
            function (ItemInterface $item) use ($query) {
                $item->expiresAfter(self::TTL);
                return $this->inner->search($query);
            }
        );
    }
}
