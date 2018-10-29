<?php

namespace B13\Newspage\Domain\Model;

class Category extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /** @var string */
    protected $name;

    public function getName(): string
    {
        return $this->name;
    }
}
