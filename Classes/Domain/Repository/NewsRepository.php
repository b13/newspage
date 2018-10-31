<?php

namespace B13\Newspage\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

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
}
