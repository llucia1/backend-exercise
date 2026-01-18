<?php
declare(strict_types=1);

namespace Library\Books\Domain\Repository;

use Library\Books\Domain\Model\BookModel;

interface IBookRepository
{
    public function findOneById(int $id): ?BookModel;
}
