<?php

declare(strict_types=1);

defined('TYPO3') or die('Access denied.');

(function () {
    $dokType = '24';

    $newsType = [
        'label' => 'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:news',
        'value' => $dokType,
        'icon' => 'apps-pagetree-newspage-page',
        'group' => 'default',
    ];

    // adding the new doktypes to the type select
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem('pages', 'doktype', $newsType);

    $GLOBALS['TCA']['pages']['ctrl']['typeicon_classes'][$dokType] = 'apps-pagetree-newspage-page';
    $GLOBALS['TCA']['pages']['ctrl']['typeicon_classes'][$dokType . '-hideinmenu'] = 'apps-pagetree-newspage-page-hideinmenu';

    $GLOBALS['TCA']['pages']['types'][$dokType]['showitem'] = str_replace('abstract,', '', $GLOBALS['TCA']['pages']['types'][1]['showitem']);

    // make title required for news and allow only one image in the media field to be used as the teaser image
    $GLOBALS['TCA']['pages']['types'][$dokType]['columnsOverrides'] = [
        'title' => [
            'config' => [
                'eval' => 'required',
            ],
        ],
        'media' => [
            'description' => 'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:media.description',
            'config' => [
                'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
            ],
        ],
    ];

    $columns = [
        'tx_newspage_date' => [
            'label' => 'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:news.date',
            'l10n_mode' => 'exclude',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
                'dbType' => 'datetime',
                'required' => true,
            ],
        ],
        'tx_newspage_categories' => [
            'label' => 'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:categories',
            'l10n_mode' => 'exclude',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'default' => 0,
                'foreign_table' => 'tx_newspage_domain_model_category',
                'foreign_table_where' => 'tx_newspage_domain_model_category.sys_language_uid IN (-1,0)',
            ],
        ],
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $columns);

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'pages',
        'tx_newspage',
        'tx_newspage_date,--linebreak--,tx_newspage_categories,--linebreak--,
    abstract;LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:news.abstract;'
    );

    // This palette is used to show in layout view
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'pages',
        'tx_newspage_layout',
        'title,abstract,--linebreak--,tx_newspage_date,tx_newspage_categories,--linebreak--,media,--linebreak--,slug;'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        '--palette--;LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:news;tx_newspage,',
        $dokType,
        'after:title'
    );
})();
