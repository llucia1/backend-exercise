<?php
declare(strict_types=1);

namespace Library\Books\Domain\Exception;

use Exception;

class BookNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Book not found');
    }
}