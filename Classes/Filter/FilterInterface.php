<?php

namespace B13\Newspage\Filter;

/*
  * This file is part of TYPO3 CMS-based extension "newspage" by b13.
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 2
  * of the License, or any later version.
  */

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;

interface FilterInterface
{
    public function getItems(): array;

    public function getQueryConstraint($filter, QueryInterface $query): ?ConstraintInterface;
}
