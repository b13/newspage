<?php

namespace B13\Newspage\Domain\Repository;

class NewsRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    public function createQuery()
    {
        $query = parent::createQuery();
        $query->matching(
          $query->equals('doktype', 24)
        );

        return $query;
    }
}
