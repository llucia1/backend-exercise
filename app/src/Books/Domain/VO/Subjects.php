<?php
declare(strict_types=1);

namespace Library\Books\Domain\VO;

final class Subjects
{
    /** @var Subject[] */
    private array $items;

    /**
     * @param Subject[] $items
     */
    public function __construct(array $items)
    {
        $this->items = array_values($items);
    }

    /**
     * @return Subject[]
     */
    public function items(): array
    {
        return $this->items;
    }

    /**
     * @return string[]
     */
    public function toStrings(): array
    {
        return array_map(
            static fn (Subject $s) => $s->value(),
            $this->items
        );
    }
}
