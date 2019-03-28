<?php

namespace B13\Newspage\Filter;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;

interface FilterInterface
{
    public function getItems(): array;

    public function getQueryConstraint($filter, QueryInterface $query): ?ConstraintInterface;
}
