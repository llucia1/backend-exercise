<?php
declare(strict_types=1);

namespace Library\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpKernel\KernelInterface;

final class ApiContext implements Context
{
    private KernelBrowser $client;
    private int $statusCode = 0;
    private string $content = '';

    public function __construct(KernelInterface $kernel)
    {
        // SymfonyExtension expone el kernel como servicio: fob_symfony.kernel
        // y lo puede autoinyectar aquí.
        $this->client = new KernelBrowser($kernel);
    }

    /** @When I request GET :path */
    public function iRequestGet(string $path): void
    {
        $this->client->request('GET', $path);

        $response = $this->client->getResponse();
        $this->statusCode = $response->getStatusCode();
        $this->content = (string) $response->getContent();
    }

    /** @Then the response status code should be :code */
    public function statusShouldBe(int $code): void
    {
        Assert::assertSame($code, $this->statusCode, $this->content);
    }

    /** @Then the response should be valid JSON */
    public function shouldBeValidJson(): void
    {
        $data = json_decode($this->content, true);
        Assert::assertIsArray($data, 'Invalid JSON: ' . $this->content);
    }

    /** @Then the JSON should contain key :key */
    public function jsonShouldContainKey(string $key): void
    {
        $data = json_decode($this->content, true);
        Assert::assertIsArray($data, 'Invalid JSON: ' . $this->content);
        Assert::assertArrayHasKey($key, $data);
    }

    /** @Then the JSON key :key should be an array */
    public function jsonKeyShouldBeAnArray(string $key): void
    {
        $data = json_decode($this->content, true);
        Assert::assertIsArray($data);
        Assert::assertIsArray($data[$key]);
    }
}
