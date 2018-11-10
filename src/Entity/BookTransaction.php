<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookTransactionRepository")
 * @ORM\Table(name="`book_transaction`")
 */
class BookTransaction
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", nullable=false)
     */
    private $requesterId;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", nullable=false)
     */
    private $keeperId;

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=24, nullable=false)
     */
    private $bookId;

    /**
     * @return mixed
     */
    public function getRequesterId()
    {
        return $this->requesterId;
    }

    /**
     * @param mixed $requesterId
     */
    public function setRequesterId($requesterId): self
    {
        $this->requesterId = $requesterId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getKeeperId()
    {
        return $this->keeperId;
    }

    /**
     * @param mixed $keeperId
     */
    public function setKeeperId($keeperId): self
    {
        $this->keeperId = $keeperId;

        return $this;
    }

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
}
