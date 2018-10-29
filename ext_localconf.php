<?php

defined('TYPO3_MODE') or die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'B13.Newspage',
    'List',
    ['News' => 'list']
    //[], \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT TODO: this might be prettier and easier
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'B13.Newspage',
    'Latest',
    ['News' => 'latest']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'B13.Newspage',
    'Teaser',
    ['News' => 'teaser']
);
