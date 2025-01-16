<?php

defined('TYPO3') or die('Access denied.');

(function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Newspage',
        'List',
        [\B13\Newspage\Controller\NewsController::class => 'list'],
        [\B13\Newspage\Controller\NewsController::class => 'list'],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Newspage',
        'Latest',
        [\B13\Newspage\Controller\NewsController::class => 'latest'],
        [],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Newspage',
        'Teaser',
        [\B13\Newspage\Controller\NewsController::class => 'teaser'],
        [],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    B13\Newspage\Service\FilterService::registerFilter(
        'Date',
        \B13\Newspage\Filter\DateFilter::class,
        'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:news.date'
    );
    B13\Newspage\Service\FilterService::registerFilter(
        'Category',
        \B13\Newspage\Filter\CategoryFilter::class,
        'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:category',
        'EXT:newspage/Configuration/FlexForms/Filter/Category.xml'
    );
    B13\Newspage\Service\FilterService::registerFilter(
        'Categories',
        \B13\Newspage\Filter\CategoriesFilter::class,
        'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:filter.categories'
    );
})();
