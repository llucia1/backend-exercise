<?php
declare(strict_types=1);

namespace Library\Books\Presentation\Rest\V1;

use Library\Books\Application\Service\GetBookByIdService;
use Library\Books\Domain\Exception\BookNotFoundException;
use Library\Books\Domain\Model\PersonModel;
use Library\Books\Domain\VO\BookId;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Schema;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_v1_')]
final class GetBookById extends AbstractController
{
    public function __construct(
        private readonly GetBookByIdService $service,
        private readonly LoggerInterface $logger,
    ) {}

    #[Get(
        summary: "Get one Book by id",
        description: "Returns a book from Gutendex by its Project Gutenberg ID",
        tags: ["Books"],
        responses: [
            new OAResponse(
                response: 200,
                description: "Success",
                content: new MediaType(
                    mediaType: "application/json",
                    schema: new Schema(
                        type: "object",
                        example: [
                            "id" => 1342,
                            "title" => "Pride and Prejudice",
                            "subjects" => ["Courtship", "Love stories"],
                            "authors" => [
                                ["name" => "Austen, Jane", "birth_year" => 1775]
                            ]
                        ]
                    )
                )
            ),
            new OAResponse(
                response: 400,
                description: "Invalid id",
                content: new MediaType(
                    mediaType: "application/json",
                    schema: new Schema(example: ["error" => "Invalid book id"])
                )
            ),
            new OAResponse(response: 404, description: "Book not found"),
            new OAResponse(response: 500, description: "Internal server error"),
        ]
    )]
    #[Route('/v1/books/{id}', name: 'get_book', methods: ['GET'])]
    public function __invoke(string $id): JsonResponse
    {
        try {
            $bookId = new BookId((int) $id);
            $book   = $this->service->__invoke($bookId);
            
            return new JsonResponse([
                'id'       => $book->id(),
                'title'    => $book->title(),
                'subjects' => $book->subjects(),
                'authors'  =>  array_map(
                                            static fn(PersonModel $a) => [
                                                'name' => $a->name(),
                                                'birth_year' => $a->birthYear(),
                                                'death_year' => $a->deathYear(),
                                            ],
                                            $book->authors()
                                        ),
            ], Response::HTTP_OK);

        } catch (BookNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}
