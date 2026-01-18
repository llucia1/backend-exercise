<?php
declare(strict_types=1);

namespace Library\Books\Domain\VO;


final class SearchQuery
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if ($value === '') {
            throw new \InvalidArgumentException('Invalid search query');
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
