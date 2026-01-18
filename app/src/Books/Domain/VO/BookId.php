<?php
declare(strict_types=1);

namespace Library\Books\Domain\VO;

final class BookId
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Invalid book id');
        }

        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }
}
