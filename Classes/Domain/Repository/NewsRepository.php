<?php
declare(strict_types=1);

namespace B13\Newspage\Domain\Repository;

/*
 * This file is part of TYPO3 CMS-based extension "newspage" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class NewsRepository extends Repository
{
    protected $defaultOrderings = [
        'tx_newspage_date' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
    ];

    public function createQuery()
    {
        $query = parent::createQuery();
        $query->matching(
            $query->equals('doktype', 24)
        );

        return $query;
    }

    public function findLatest(array $options = []): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($this->getConstraints($options['filter'], $query));

        if ($limit = $options['limit']) {
            $query->setLimit($limit);
        }

        if ($offset = $options['offset']) {
            $query->setOffset($offset);
        }

        return $query->execute();
    }

    public function findFiltered(array $options = []): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($this->getConstraints($options['filter'], $query));

        if ($limit = $options['limit']) {
            $query->setLimit($limit);
        }

        if ($offset = $options['offset']) {
            $query->setOffset($offset);
        }

        return $query->execute();
    }

    public function countFiltered(array $filter): int
    {
        $query = $this->createQuery();
        $query->matching($this->getConstraints($filter, $query));
        return $query->count();
    }

    protected function getConstraints(array $filter, QueryInterface $query): ConstraintInterface
    {
        $constraints = [];
        $constraints[] = $query->getConstraint();
        foreach ($filter as $field => $value) {
            if ($value) {
                $filterObj = GeneralUtility::makeInstance(ObjectManager::class)
                    ->get($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newspage']['filters'][ucfirst($field)]['class']);

                $constraint = call_user_func([$filterObj, 'getQueryConstraint'], $value, $query);
                if (!is_null($constraint)) {
                    $constraints[] = $constraint;
                }
            }
        }

        return $query->logicalAnd($constraints);
    }
}
