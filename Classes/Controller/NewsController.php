<?php

declare(strict_types=1);

namespace B13\Newspage\Controller;

/*
 * This file is part of TYPO3 CMS-based extension "newspage" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use B13\Newspage\Domain\Repository\NewsRepository;
use B13\Newspage\Event\CreatingPaginationEvent;
use B13\Newspage\Service\FilterService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;

class NewsController extends ActionController
{
    protected array $preFilters = [];

    public function __construct(protected NewsRepository $newsRepository) {}

    public function listAction(array $filter = [], int $page = 1): ResponseInterface
    {
        foreach ($this->settings['prefilters'] ?? [] as $type => $value) {
            if (!empty($value)) {
                $filter[$type] = $value;
                $this->preFilters[] = $type;
            }
        }

        $options['filter'] = $filter;

        $news = $this->newsRepository->findFiltered($options);

        if (($this->settings['filter']['show'] ?? false) && ($this->settings['filter']['by'] ?? '') !== '') {
            $this->view->assign('filterOptions', $this->getFilterOptions());
        }

        $paginator = new QueryResultPaginator($news, $page, (int)($this->settings['limit'] ?? 10));
        $event = $this->eventDispatcher->dispatch(
            new CreatingPaginationEvent($paginator)
        );
        $pagination = $event->getPagination() ?? new SimplePagination($paginator);
        $contentObjectData = $this->request->getAttribute('currentContentObject');
        $this->view->assignMultiple(
            [
                'news' => $news,
                'filter' => $filter,
                'paginator' => $paginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'contentObjectData' => $contentObjectData ? $contentObjectData->data : null,
            ]
        );
        return $this->htmlResponse();
    }

    public function teaserAction(): ResponseInterface
    {
        $contentObjectData = $this->request->getAttribute('currentContentObject');
        $uids = GeneralUtility::intExplode(',', $this->settings['news'] ?? '', true);
        $news = [];
        foreach ($uids as $uid) {
            $news[] = $this->newsRepository->findByUid($uid);
        }
        $this->view->assignMultiple(
            [
                'news' => $news,
                'contentObjectData' => $contentObjectData ? $contentObjectData->data : null,
            ]
        );
        return $this->htmlResponse();
    }

    public function latestAction(): ResponseInterface
    {
        $contentObjectData = $this->request->getAttribute('currentContentObject');
        $settings = [
            'limit' => (int)($this->settings['limit'] ?? 0),
            'filter' => [
                'category' => (int)($this->settings['category'] ?? 0),
            ],
        ];
        $news = $this->newsRepository->findLatest($settings);
        $this->view->assignMultiple(
            [
                'news' => $news,
                'contentObjectData' => $contentObjectData ? $contentObjectData->data : null,
            ]
        );
        return $this->htmlResponse();
    }

    protected function getFilterOptions(): array
    {
        $filterOptions = [];
        foreach (explode(',', $this->settings['filter']['by'] ?? []) as $filter) {
            $preFilterKey = strtolower($filter);
            if (!in_array($preFilterKey, $this->preFilters) || empty($this->settings['prefilters'][$preFilterKey])) {
                $filterOptions[$filter]['items'] = FilterService::getFilterOptionsForFluid($filter);
            }
        }
        return $filterOptions;
    }
}
