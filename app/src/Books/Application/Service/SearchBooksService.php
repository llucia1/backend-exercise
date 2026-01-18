<?php
declare(strict_types=1);

namespace Library\Books\Application\Service;

use Library\Books\Application\Response\BookCollectionResponse;
use Library\Books\Domain\Exception\ListBooksEmptyException;
use Library\Books\Domain\Repository\IBookRepository;
use Library\Books\Domain\VO\SearchQuery;
use Psr\Log\LoggerInterface;

final class SearchBooksService
{
    public function __construct(
        private readonly IBookRepository $bookRepository,
        private readonly LoggerInterface $logger,
    ) {}
    
    public function __invoke(SearchQuery $query): array
    {
        return $this->search($query);
    }

    public function search(SearchQuery $query): array
    {

        $this->logger->info('Start Service Search Books -> ' . $query->value());

        $books = $this->bookRepository->search($query->value());

        return empty($books)
            ? throw new ListBooksEmptyException()
            : BookCollectionResponse::getBooksModel($books)->get();
    }
}
