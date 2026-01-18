<?php
declare(strict_types=1);

namespace Library\Books\Application\Service;

use Library\Books\Application\Response\BookResponse;
use Library\Books\Domain\Exception\BookNotFoundException;
use Library\Books\Domain\Repository\IBookRepository;
use Library\Books\Domain\VO\BookId;
use Psr\Log\LoggerInterface;

final class GetBookByIdService
{
    public function __construct(
        private readonly IBookRepository $bookRepository,
        private readonly LoggerInterface $logger,
    ) {}

    public function __invoke(BookId $id): BookResponse
    {
        return $this->getById($id);
    }

    public function getById(BookId $id): BookResponse
    {
        $this->logger->info("Start Service Get One Book by id -> " . $id->value());

        $book = $this->bookRepository->findOneById($id->value());

        return is_null($book)
            ? throw new BookNotFoundException()
            : BookResponse::byModel($book);
    }
}