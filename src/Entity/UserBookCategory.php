<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserBookCategoryRepository")
 * @ORM\Table(name="user_book_category")
 */
class UserBookCategory
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=24, nullable=false)
     */
    private $bookId;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", nullable=false)
     */
    private $userId;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", nullable=false)
     */
    private $categoryId;

    /**
     * @return mixed
     */
    public function getBookId()
    {
        return $this->bookId;
    }

    /**
     * @param mixed $bookId
     */
    public function setBookId($bookId): self
    {
        $this->bookId = $bookId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param mixed $categoryId
     */
    public function setCategoryId($categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }
}
