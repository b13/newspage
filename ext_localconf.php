<?php

defined('TYPO3') or die('Access denied.');

(function () {
    // Hook to dynamically add filters to flexform
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][\TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools::class]['flexParsing'][]
        = \B13\Newspage\Hooks\PrefilterFlexform::class;

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

    $typo3Version = new \TYPO3\CMS\Core\Information\Typo3Version();
    if (version_compare($typo3Version->getVersion(), '11.0.0', '<')) {
        $icons = [
            'apps-pagetree-newspage-page' => 'apps-pagetree-newspage-page',
            'apps-pagetree-newspage-page-hideinmenu' => 'apps-pagetree-newspage-page-hideinmenu',
            'apps-pagetree-newspage-article' => 'apps-pagetree-newspage-article',
            'apps-pagetree-newspage-article-hideinmenu' => 'apps-pagetree-newspage-article-hideinmenu',
            'apps-pagetree-newspage-category' => 'apps-pagetree-newspage-category',
            'apps-pagetree-newspage-category-hideinmenu' => 'apps-pagetree-newspage-category-hideinmenu',
            'apps-pagetree-newspage-overview' => 'apps-pagetree-newspage-overview',
            'apps-pagetree-newspage-overview-hideinmenu' => 'apps-pagetree-newspage-overview-hideinmenu',
            'apps-pagetree-newspage-tag' => 'apps-pagetree-newspage-tag',
            'apps-pagetree-newspage-tag-hideinmenu' => 'apps-pagetree-newspage-tag-hideinmenu',
            'mimetypes-newspage' => 'mimetypes-newspage',
            'apps-pagetree-folder-newspages' => 'apps-pagetree-folder-newspages',
        ];
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        foreach ($icons as $identifier => $path) {
            if (!$iconRegistry->isRegistered($identifier)) {
                $iconRegistry->registerIcon(
                    $identifier,
                    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
                    ['source' => 'EXT:newspage/Resources/Public/Icons/' . $path . '.svg']
                );
            }
        }
    }
})();
