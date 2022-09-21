<?php
declare(strict_types=1);

namespace B13\Newspage\Mvc\View;

/*
 * This file is part of TYPO3 CMS-based extension "newspage" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use TYPO3\CMS\Extbase\Mvc\View\JsonView as ExtbaseJsonView;

class JsonView extends ExtbaseJsonView
{
    /**
     * @var array
     */
    protected $configuration = [
        'news' => [
            '_descendAll' => [
                '_exclude' => ['pid', 'media'],
                '_descend' => [
                    'date' => [],
                    'categories' => [
                        '_descendAll' => [
                            '_exclude' => ['pid'],
                        ]
                    ],
                    'category' => [
                        '_exclude' => ['pid'],
                    ],
                ]
            ]
        ]
    ];
}
