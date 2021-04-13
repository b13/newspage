<?php
defined('TYPO3_MODE') or die('Access denied.');

(function () {
    $dokType = 24;
    $newsType = ['LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:news', $dokType];

    // adding the new doktypes to the type select
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem('pages', 'doktype', $newsType);

    $GLOBALS['TCA']['pages']['types'][$dokType]['showitem'] = str_replace('abstract,', '', $GLOBALS['TCA']['pages']['types'][1]['showitem']);

    // make title required for news and allow only one image in the media field to be used as the teaser image
    $GLOBALS['TCA']['pages']['types'][$dokType]['columnsOverrides'] = [
        'title' => [
            'config' => [
                'eval' => 'required'
            ]
        ],
        'media' => [
            'description' => 'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:media.description',
            'config' => [
                'overrideChildTca' => [
                    'columns' => [
                        'uid_local' => [
                            'config' => [
                                'appearance' => [
                                    'elementBrowserAllowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ];

    $columns = [
        'tx_newspage_date' => [
            'label' => 'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:news.date',
            'l10n_mode' => 'exclude',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'dbType' => 'datetime',
                'eval' => 'datetime,required'
            ]
        ],
        'tx_newspage_categories' => [
            'label' => 'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:categories',
            'l10n_mode' => 'exclude',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'default' => 0,
                'foreign_table' => 'tx_newspage_domain_model_category',
                'foreign_table_where' => 'tx_newspage_domain_model_category.sys_language_uid IN (-1,0)'
            ]
        ]
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $columns);

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'pages',
        'tx_newspage',
        'tx_newspage_date,--linebreak--,tx_newspage_categories,--linebreak--,
    abstract;LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:news.abstract;'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        '--palette--;LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:news;tx_newspage,',
        $dokType,
        'after:title'
    );
})();
