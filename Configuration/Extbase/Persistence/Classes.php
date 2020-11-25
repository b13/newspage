<?php
declare(strict_types = 1);

return [
    \B13\Newspage\Domain\Model\News::class => [
        'tableName' => 'pages',
        'properties' => [
            'date' => [
                'fieldName' => 'tx_newspage_date',
            ],
            'categories' => [
                'fieldName' => 'tx_newspage_categories',
            ],
        ],
    ],
];
