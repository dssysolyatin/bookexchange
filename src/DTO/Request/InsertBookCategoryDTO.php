<?php

namespace App\DTO\Request;

use App\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;

class InsertBookCategoryDTO
{
    /**
     * @Assert\NotNull()
     * @Assert\Type(type="string")
     */
    public $bookId;

    /**
     * @Assert\NotNull()
     * @Assert\Choice(
     *     choices = Category::SUPPORT_TYPES,
     *     message="Invalid category type."
     * )
     */
    public $categoryId;
}
