<?php
declare(strict_types=1);

namespace Library\Books\Domain\VO;

final class Subject
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
