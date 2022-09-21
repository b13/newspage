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

use B13\Newspage\Domain\DTO\JsonNews;
use B13\Newspage\Domain\Model\News;
use B13\Newspage\Domain\Repository\NewsRepository;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\JsonView;
use TYPO3\CMS\Extbase\Service\ImageService;

class JsonController extends ActionController
{
    /**
     * @var JsonView
     */
    protected $view;

    protected $defaultViewObjectName = JsonView::class;

    /**
     * @var NewsRepository
     */
    protected $newsRepository;

    /**
     * @var ImageService
     */
    protected $imageService;

    public function injectNewsRepository(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function injectImageService(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function getNewsAction(): void
    {
        $this->view->setVariablesToRender(['news']);

        $news = $this->newsRepository->findAll();

        // we need to prepare the teaser image for the JSON here
        $return = [];
        /** @var News $report */
        foreach ($news as $report) {
            if ($report->getMedia() !== null) {
                $teaserImageUrl = $this->processTeaserImage($report->getMedia());
            } else {
                $teaserImageUrl = '';
            }
            $return[] = new JsonNews($report, $teaserImageUrl);
        }

        $this->view->assign('news', $return);
    }

    protected function processTeaserImage(FileReference $reference): string
    {
        $image = $reference->getOriginalResource();
        $cropString = '';
        if ($image->hasProperty('crop') && $image->getProperty('crop')) {
            $cropString = $image->getProperty('crop');
        }
        $cropVariantCollection = CropVariantCollection::create((string)$cropString);
        $cropVariant =  $this->settings['processingInstructions']['cropVariant'] ?: 'default';
        $cropArea = $cropVariantCollection->getCropArea($cropVariant);

        $processedImage = $this->imageService->applyProcessingInstructions(
            $image,
            [
                'width' => $this->settings['processingInstructions']['width'],
                'height' => $this->settings['processingInstructions']['height'],
                'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image)
            ]
        );
        return $processedImage->getPublicUrl();
    }
}
