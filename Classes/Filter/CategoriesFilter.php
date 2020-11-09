<?php

namespace B13\Newspage\Filter;

use B13\Newspage\Domain\Repository\CategoryRepository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;

class CategoriesFilter implements FilterInterface
{
    /**
     * @var \B13\Newspage\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository;

    public function injectCategoryRepository(CategoryRepository $categoryRepository): void
    {
        $this->categoryRepository = $categoryRepository;
    }

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
