<?php

namespace B13\Newspage\Controller;

use B13\Newspage\Service\FilterService;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class NewsController extends ActionController
{

    /**
     * @var \B13\Newspage\Domain\Repository\NewsRepository
     */
    protected $newsRepository;

    public function injectNewsRepository(\B13\Newspage\Domain\Repository\NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    /**
     * @param int $page
     * @param array $filter
     */
    public function listAction(int $page = 1, array $filter = [])
    {
        $limit = (int)$this->settings['limit'];
        if (($category = (int)$this->settings['category']) > 0) {
            $filter['category'] = $category;
        }
        $options = [
            'limit' => $limit,
            'offset' => ($page - 1) * $limit,
            'filter' => $filter
        ];

        if ($this->settings['filter']['show']) {
            $this->view->assign('filterOptions', $this->getFilterOptions());
        }
        $news = $this->newsRepository->findForList($options);
        $this->view->assignMultiple([
            'news' => $news,
            'filter' => $filter,
            'page' => $page,
            'lastpage' => ceil($this->newsRepository->countFiltered($filter) / $limit),
        ]);
    }

    public function teaserAction()
    {
        $report = $this->newsRepository->findByUid((int)$this->settings['news']);
        $this->view->assign('report', $report);
    }

    public function latestAction()
    {
        $settings = [
            'limit' => (int)$this->settings['limit'],
            'filter' => [
                'category' => (int)$this->settings['category']
            ]
        ];
        $news = $this->newsRepository->findLatest($settings);
        $this->view->assign('news', $news);
    }

    protected function getFilterOptions(): array
    {
        $filterOptions = [];
        foreach (explode(',', $this->settings['filter']['by']) as $filter) {
            $filterOptions[$filter]['items'] = FilterService::getFilterOptionsForFluid($filter);
        }
        return $filterOptions;
    }
}
