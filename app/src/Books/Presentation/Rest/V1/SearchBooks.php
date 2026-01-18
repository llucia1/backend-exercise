<?php
declare(strict_types=1);

namespace Library\Books\Presentation\Rest\V1;

use Library\Books\Application\Service\SearchBooksService;
use Library\Books\Application\Response\BookCollectionResponse;
use Library\Books\Application\Response\BookResponse;
use Library\Books\Domain\Exception\ListBooksEmptyException;
use Library\Books\Domain\Model\PersonModel;
use Library\Books\Domain\VO\SearchQuery;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_v1_')]
final class SearchBooks extends AbstractController
{
    public function __construct(
        private readonly SearchBooksService $service,
    ) {}

    #[OA\Get(
        summary: 'Search books',
        description: 'Searches books by title and author using Gutendex (Lists of Books)',
        tags: ['Books']
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(
            type: 'object',
            example: [
                'items' => [
                    [
                        'id' => 1342,
                        'title' => 'Pride and Prejudice',
                        'subjects' => ['Courtship', 'Love stories'],
                        'authors' => [
                            ['name' => 'Austen, Jane', 'birth_year' => 1775, 'death_year' => 1817],
                        ],
                    ],
                ],
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Invalid query',
        content: new OA\JsonContent(example: ['error' => 'Invalid search query'])
    )]
    #[OA\Response(response: 500, description: 'Internal server error')]
    #[Route('/v1/books/search/{query}', name: 'search_books', methods: ['GET'])]
    public function __invoke(string $query): JsonResponse
    {
        try {
            $books = ($this->service)( new SearchQuery($query));
 
            return new JsonResponse(
                array_map(
                    fn(BookResponse $book) : array => [
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
                    ],
                    $books
                ),                Response::HTTP_OK,
                ['Access-Control-Allow-Origin' => '*']
            );           

        } catch (ListBooksEmptyException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
