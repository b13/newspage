<?php

namespace B13\Newspage\Controller;

use B13\Newspage\Service\FilterService;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class NewsController
 * @package B13\Newspage\Controller
 */
class NewsController extends ActionController
{

    /**
     * @var array
     */
    protected $preFilters = [];

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
    public function listAction(array $filter = []): void
    {
        foreach ($this->settings['prefilters'] as $type => $value) {
            if ($value !== '') {
                $filter[$type] = $value;
                $this->preFilters[] = $type;
            }
        }

        $options['filter'] = $filter;

        $news = $this->newsRepository->findFiltered($options);

        if ($this->settings['filter']['show'] && $this->settings['filter']['by'] !== '') {
            $this->view->assign('filterOptions', $this->getFilterOptions());
        }
        $this->view->assignMultiple([
            'news' => $news,
            'filter' => $filter
        ]);
    }

    /**
     *
     */
    public function teaserAction(): void
    {
        $uids = explode(',', $this->settings['news']);
        $news = [];
        foreach ($uids as $uid) {
            $news[] = $this->newsRepository->findByUid((int)$uid);
        }
        $this->view->assign('news', $news);
    }

    /**
     *
     */
    public function latestAction(): void
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

    /**
     * @return array
     */
    protected function getFilterOptions(): array
    {
        $filterOptions = [];
        foreach (explode(',', $this->settings['filter']['by']) as $filter) {
            if (!in_array(strtolower($filter), $this->preFilters)) {
                $filterOptions[$filter]['items'] = FilterService::getFilterOptionsForFluid($filter);
            }
        }
        return $filterOptions;
    }
}
