<?php
declare(strict_types=1);

namespace Library\Books\Domain\VO;

final class PersonDeathYear
{
    private int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }
}
