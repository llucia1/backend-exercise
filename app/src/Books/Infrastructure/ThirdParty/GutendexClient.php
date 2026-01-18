<?php
declare(strict_types=1);

namespace Library\Books\Infrastructure\ThirdParty;

use GuzzleHttp\ClientInterface;

final class GutendexClient
{
    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly string $baseUrl = 'https://gutendex.com',
    ) {}

    /** @return array<string,mixed> */
    public function searchBooks(string $query): array
    {
        $response = $this->httpClient->request('GET', $this->baseUrl . '/books', [
            'query' => ['search' => $query],
        ]);

        /** @var array<string,mixed> $data */
        $data = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        return $data;
    }

    /** @return array<string,mixed> */
    public function getBooksByIds(array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        $response = $this->httpClient->request('GET', $this->baseUrl . '/books', [
            'query' => ['ids' => implode(',', $ids)],
        ]);

        /** @var array<string,mixed> $data */
        $data = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        return $data;
    }
}
