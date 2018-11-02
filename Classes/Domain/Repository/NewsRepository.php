<?php

namespace B13\Newspage\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;

class NewsRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
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
        $matching = $query->getConstraint();
        if (isset($options['filter'])) {
            if (($category = $options['filter']['category']) > 0) {
                $matching = $query->logicalAnd(
                    $matching,
                    $query->equals('tx_newspage_category', $category)
                );
            }
        }

        $query->matching($matching);

        if ($limit = $options['limit']) {
            $query->setLimit($limit);
        }

        return $query->execute();
    }

    public function findForList(array $options = []): QueryResultInterface
    {
        $query = $this
            ->createQuery()
            ->setLimit($options['limit'])
            ->setOffset($options['offset']);
        $query->matching($this->getConstraints($options['filter'], $query));
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
            switch ($field) {
                case 'date':
                    $date =
                        $value['year']
                            ? $value['year'] .
                            ($value['month'] ? '-' . $value['month'] . '%' : '%')
                            : '';
                    if ($date != '') {
                        $constraints[] = $query->like('tx_newspage_date', $date);
                    }
                    break;
            }
        }

        return $query->logicalAnd($constraints);
    }
}
