<?php

namespace B13\Newspage\Domain\Model;

class News extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /** @var string */
    protected $title;

    /** @var \DateTime */
    protected $date;

    /** @var \B13\Newspage\Domain\Model\Category */
    protected $category;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }
}
