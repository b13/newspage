<?php

namespace B13\Newspage\Domain\Model;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class News extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /** @var string */
    protected $title;

    /** @var \DateTime */
    protected $date;

    /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\B13\Newspage\Domain\Model\Category> */
    protected $categories;

    /** @var string */
    protected $abstract;

    /** @var \TYPO3\CMS\Extbase\Domain\Model\FileReference */
    protected $media;

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
     * @return ObjectStorage<\B13\Newspage\Domain\Model\Category>
     */
    public function getCategories(): ObjectStorage
    {
        return $this->categories;
    }

    /**
     * @param ObjectStorage $category
     */
    public function setCategories(ObjectStorage $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * @return string|null
     */
    public function getAbstract(): ?string
    {
        return $this->abstract;
    }

    /**
     * @param string $abstract
     */
    public function setAbstract(string $abstract): void
    {
        $this->abstract = $abstract;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    public function getMedia(): ?\TYPO3\CMS\Extbase\Domain\Model\FileReference
    {
        return $this->media;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $media
     */
    public function setMedia(\TYPO3\CMS\Extbase\Domain\Model\FileReference $media): void
    {
        $this->media = $media;
    }
}
