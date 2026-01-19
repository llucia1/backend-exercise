<?php
declare(strict_types=1);

namespace Library\Tests\Books\Application\Service;

use Library\Books\Application\Service\GetBookByIdService;
use Library\Books\Domain\Exception\BookNotFoundException;
use Library\Books\Domain\Model\BookModel;
use Library\Books\Domain\Repository\IBookRepository;
use Library\Books\Domain\VO\BookId;
use Library\Books\Domain\VO\BookTitle;
use Library\Books\Domain\VO\Subjects;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class GetBookByIdServiceTest extends TestCase
{
    public function test_it_returns_book_response_when_book_exists(): void
    {
        $repo = $this->createMock(IBookRepository::class);
        $logger = $this->createMock(LoggerInterface::class);

        $bookModel = new BookModel(
            new BookId(1342),
            new BookTitle('Pride and Prejudice'),
            new Subjects([]),
            []
        );

        $repo->expects($this->once())
            ->method('findOneById')
            ->with(1342)
            ->willReturn($bookModel);

        $service = new GetBookByIdService($repo, $logger);

        $response = ($service)(new BookId(1342));

        $this->assertSame(1342, $response->id());
        $this->assertSame('Pride and Prejudice', $response->title());
        $this->assertSame([], $response->subjects());
        $this->assertSame([], $response->authors());
    }

    public function test_it_throws_when_book_does_not_exist(): void
    {
        $repo = $this->createMock(IBookRepository::class);
        $logger = $this->createMock(LoggerInterface::class);

        $repo->expects($this->once())
            ->method('findOneById')
            ->with(999999)
            ->willReturn(null);

        $service = new GetBookByIdService($repo, $logger);

        $this->expectException(BookNotFoundException::class);

        ($service)(new BookId(999999));
    }
}
