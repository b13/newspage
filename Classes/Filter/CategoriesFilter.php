<?php

declare(strict_types=1);

namespace B13\Newspage\Filter;

/*
  * This file is part of TYPO3 CMS-based extension "newspage" by b13.
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 2
  * of the License, or any later version.
  */

use B13\Newspage\Domain\Repository\CategoryRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

class CategoriesFilter implements FilterInterface
{
    public function __construct(protected CategoryRepository $categoryRepository) {}

    public function getItems(): array
    {
        return $this->categoryRepository->findAll()->toArray();
    }

    public function getQueryConstraint($filter, QueryInterface $query): ConstraintInterface
    {
        $constraints = [];
        foreach ($filter as $category) {
            $constraints[] = $query->contains('categories', $category);
        }

        return $query->logicalOr(...$constraints);
    }
}
