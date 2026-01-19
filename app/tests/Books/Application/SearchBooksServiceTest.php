<?php
declare(strict_types=1);

namespace Library\Tests\Books\Application\Service;

use Library\Books\Application\Service\SearchBooksService;
use Library\Books\Domain\Exception\ListBooksEmptyException;
use Library\Books\Domain\Model\BookModel;
use Library\Books\Domain\Repository\IBookRepository;
use Library\Books\Domain\VO\BookId;
use Library\Books\Domain\VO\BookTitle;
use Library\Books\Domain\VO\SearchQuery;
use Library\Books\Domain\VO\Subjects;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class SearchBooksServiceTest extends TestCase
{
    public function test_it_returns_collection_when_books_exist(): void
    {
        $repo = $this->createMock(IBookRepository::class);
        $logger = $this->createMock(LoggerInterface::class);

        $book1 = new BookModel(
            new BookId(1),
            new BookTitle('Book 1'),
            new Subjects([]),
            []
        );

        $book2 = new BookModel(
            new BookId(2),
            new BookTitle('Book 2'),
            new Subjects([]),
            []
        );

        $repo->expects($this->once())
            ->method('search')
            ->with('dickens great')
            ->willReturn([$book1, $book2]);

        $service = new SearchBooksService($repo, $logger);

        $result = ($service)(new SearchQuery('dickens great'));

        $this->assertCount(2, $result);
        $this->assertSame(1, $result[0]->id());
        $this->assertSame('Book 1', $result[0]->title());
    }

    public function test_it_throws_when_no_books_found(): void
    {
        $repo = $this->createMock(IBookRepository::class);
        $logger = $this->createMock(LoggerInterface::class);

        $repo->expects($this->once())
            ->method('search')
            ->with('nothing')
            ->willReturn([]);

        $service = new SearchBooksService($repo, $logger);

        $this->expectException(ListBooksEmptyException::class);

        ($service)(new SearchQuery('nothing'));
    }
}
