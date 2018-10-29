<?php
defined("TYPO3_MODE") or die('Access denied.');

$dokType = 24;
$eventType = ['LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:news', $dokType];

// adding the new doktypes to the type select
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem('pages', 'doktype', $eventType);

$GLOBALS['TCA']['pages']['types'][$dokType]['showitem'] = str_replace('abstract,', '', $GLOBALS['TCA']['pages']['types'][1]['showitem']);

// make title required for news
$GLOBALS['TCA']['pages']['types'][$dokType]['columnsOverrides'] = ['title' => ['config' => ['eval' => 'required']]];

$columns = [
    'tx_newspage_date' => [
        'label' => 'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:news.date',
        'config' => [
            'type' => 'input',
            'renderType' => 'inputDateTime',
            'dbType' => 'date',
            'eval' => 'date,required'
        ]
    ],
    'tx_newspage_category' => [
        'label' => 'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:category',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:select.placeholder', 0]
            ],
            'foreign_table' => 'tx_newspage_domain_model_category'
        ]
    ]
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $columns);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'pages',
    'tx_newspage',
    'tx_newspage_date,--linebreak--,tx_newspage_category,--linebreak--,
    abstract;LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:news.abstract;'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    '--palette--;LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:news;tx_newspage,',
    $dokType,
    'after:title'
);
