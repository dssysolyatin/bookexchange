<?php

namespace App\DTO\Request;

use App\Entity\Book;
use App\Entity\User;

class CreateBookTransaction
{
    /**
     * @var Book
     */
    public $book;

    /**
     * @var User
     */
    public $requester;

    /**
     * @var User
     */
    public $keeper;
}
