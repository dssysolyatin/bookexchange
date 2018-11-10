<?php


namespace App\DTO\Response;


use Google_Service_Books_Volume;

class Book
{
    public $title;

    public $description;

    public $authors = [];

    public $imageLinks = [];

    /**
     * @param Google_Service_Books_Volume $volume
     */
    public static function createByVolume(Google_Service_Books_Volume $volume): self
    {
        $volumeInfo = $volume->getVolumeInfo();

        $book = new self();

        $book->id = $volume->getId();
        $book->title = $volumeInfo->getTitle();
        $book->description = $volumeInfo->getDescription();
        $book->authors = $volumeInfo->getAuthors();
        $book->imageLinks = $volumeInfo->getImageLinks();

        return $book;
    }

    public static function createByBookEntity(\App\Entity\Book $bookEntity): self
    {
        $info = $bookEntity->getInfo();

        $book = new self();

        $book->id = $bookEntity->getId();
        $book->title = $info['title'];
        $book->description = $info['description'];
        $book->authors = $info['authors'];
        $book->imageLinks = $info['imageLinks'];

        return $book;
    }
}