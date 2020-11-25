<?php

namespace B13\Newspage\Filter;

/*
  * This file is part of TYPO3 CMS-based extension "newspage" by b13.
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 2
  * of the License, or any later version.
  */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;

class DateFilter implements FilterInterface
{


    public function getItems(): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('pages')->createQueryBuilder();
        $years = $queryBuilder
            ->selectLiteral('DISTINCT(YEAR(tx_newspage_date))')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq('doktype', 24)
            )
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        // in order for the f:form.select view helper to send and display the correct values,
        // we have to make the value and key the same
        $return = [];
        foreach ($years as $year) {
            $return[$year] = $year;
        }
        return $return;
    }


    public function getQueryConstraint($filter, QueryInterface $query): ?ConstraintInterface
    {
        $date =
            $filter['year']
                ? $filter['year'] .
                ($filter['month'] ? '-' . $filter['month'] . '%' : '%')
                : '';
        if ($date != '') {
            return $query->like('tx_newspage_date', $date);
        }
        return null;
    }
}
