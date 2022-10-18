<?php
declare(strict_types=1);

namespace B13\Newspage\Domain\Model;

/*
 * This file is part of TYPO3 CMS-based extension "newspage" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class News extends AbstractEntity
{
    protected string $title;
    protected DateTime $date;
    protected string $abstract;
    protected FileReference $media;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\B13\Newspage\Domain\Model\Category>
     */
    protected ObjectStorage $categories;

    public function __construct()
    {
        $this->categories = new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\B13\Newspage\Domain\Model\Category>
     */
    public function getCategories(): ObjectStorage
    {
        return $this->categories;
    }

    public function getCategory(): ?Category
    {
        if ($this->categories->count() > 0) {
            return $this->categories->offsetGet(0);
        }
        return null;
    }

    public function getAbstract(): ?string
    {
        return $this->abstract;
    }

    public function getMedia(): ?\TYPO3\CMS\Extbase\Domain\Model\FileReference
    {
        return $this->media;
    }
}
