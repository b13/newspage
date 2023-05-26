<?php

declare(strict_types=1);

namespace B13\Newspage\EventListener;

use B13\Newspage\Event\CreatingPaginationEvent;
use TYPO3\CMS\Core\Pagination\SimplePagination;

final class SimplePaginationProvider
{
    public function __invoke(CreatingPaginationEvent $event): void
    {
        $event->setPagination(new SimplePagination($event->getPaginator()));
    }
}
