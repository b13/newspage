<?php

namespace B13\Newspage\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class NewsController extends ActionController
{

    /**
     * @var \B13\Newspage\Domain\Repository\NewsRepository
     * @inject
     */
    protected $newsRepository;

    public function listAction()
    {
        if (($category = (int)$this->settings['category']) > 0) {
            $news = $this->newsRepository->findByCategory($category);
        } else {
            $news = $this->newsRepository->findAll();
        }
        $this->view->assign('news', $news);
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
}
