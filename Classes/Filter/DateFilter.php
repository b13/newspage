<?php

namespace B13\Newspage\Filter;

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
            ->execute();
        return $years->fetchAll(\PDO::FETCH_ASSOC);
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
