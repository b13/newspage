<?php

declare(strict_types=1);

namespace B13\Newspage\Event;

use Psr\EventDispatcher\StoppableEventInterface;
use TYPO3\CMS\Core\Pagination\PaginationInterface;
use TYPO3\CMS\Core\Pagination\PaginatorInterface;

final class CreatingPaginationEvent implements StoppableEventInterface
{
    private PaginatorInterface $paginator;
    private ?PaginationInterface $pagination = null;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function getPaginator(): PaginatorInterface
    {
        return $this->paginator;
    }

    public function getPagination(): ?PaginationInterface
    {
        return $this->pagination;
    }

    public function setPagination(PaginationInterface $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function isPropagationStopped(): bool
    {
        return $this->pagination instanceof PaginationInterface;
    }
}
