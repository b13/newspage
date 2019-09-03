<?php

defined('TYPO3_MODE') or die('Access denied.');

// Hook to dynamically add filters to flexform
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][\TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools::class]['flexParsing'][]
    = \B13\Newspage\Hooks\PrefilterFlexform::class;

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'B13.Newspage',
    'List',
    ['News' => 'list'],
    ['News' => 'list'],
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'B13.Newspage',
    'Latest',
    ['News' => 'latest'],
    [],
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'B13.Newspage',
    'Teaser',
    ['News' => 'teaser'],
    [],
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);


B13\Newspage\Service\FilterService::registerFilter(
    'Date',
    \B13\Newspage\Filter\DateFilter::class,
    'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:settings.filter.by.date'
);
B13\Newspage\Service\FilterService::registerFilter(
    'Category',
    \B13\Newspage\Filter\CategoryFilter::class,
    'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:settings.filter.by.category',
    'EXT:newspage/Configuration/FlexForms/Filter/Category.xml'
);
