<?php

declare(strict_types=1);

namespace B13\Newspage\Domain\DTO;

use B13\Newspage\Domain\Model\News;

class JsonNews extends News
{
    /**
     * @var string
     */
    protected $teaserImageUrl;

    public function __construct(News $report, string $teaserImageUrl)
    {
        $this->title = $report->getTitle();
        $this->date = $report->getDate();
        $this->abstract = $report->getAbstract();
        $this->media = $report->getMedia();
        $this->categories = $report->getCategories();
        $this->teaserImageUrl = $teaserImageUrl;
    }
}