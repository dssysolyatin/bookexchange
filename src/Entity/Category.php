<?php

namespace App\Entity;

class Category
{
    const FREE = 0;
    const MY = 1;
    const READING = 2;

    const SUPPORT_TYPES = [
        self::FREE,
        self::MY,
        self::READING,
    ];
}
