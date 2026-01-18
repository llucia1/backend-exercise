<?php
declare(strict_types=1);

namespace Library\Books\Domain\Exception;

use Exception;

class ListBooksEmptyException extends Exception
{
    public function __construct()
    {
        parent::__construct('The list of books is empty');
    }
}