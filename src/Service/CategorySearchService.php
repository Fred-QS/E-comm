<?php

namespace App\Service;

use App\Entity\Category;

class CategorySearchService
{
    /**
     * @var ?string
     */
    public ?string $string = '';
    /**
     * @var ?Category[]
     */
    public ?array $categories = [];
}