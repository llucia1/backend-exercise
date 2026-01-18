<?php
declare(strict_types=1);

namespace Library\Books\Domain\VO;

final class PersonName
{
    private string $value;

    public function __construct(string $value)
    {
        if ($value === '') {
            throw new \InvalidArgumentException('Invalid person name');
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
