<?php

declare(strict_types=1);

$EM_CONF[$_EXTKEY] = [
    'title' => 'News Page',
    'description' => 'Use pages as news records.',
    'category' => 'fe',
    'version' => '3.0.0',
    'state' => 'stable',
    'clearcacheonload' => 1,
    'author' => 'b13 GmbH',
    'author_email' => 'typo3@b13.com',
    'author_company' => 'b13 GmbH',
    'constraints' => [
        'depends' => [
            'typo3' => '',
        ],
    ],
];
