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
     * @param array $filter
     */
    public function listAction(array $filter = [])
    {
        if (($category = (int)$this->settings['category']) > 0) {
            $filter['category'] = $category;
        }
        $news = $this->newsRepository->findFiltered($filter);

        if ($this->settings['filter']['show']) {
            $this->view->assign('filterOptions', $this->getFilterOptions());
        }
        $this->view->assignMultiple([
            'news' => $news,
            'filter' => $filter
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
