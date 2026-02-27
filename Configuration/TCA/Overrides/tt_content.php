<?php

declare(strict_types=1);

defined('TYPO3') or die('Access denied.');

(function () {
    $ext = 'Newspage';
    $locallang = 'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf';
    $flexformPath = 'FILE:EXT:newspage/Configuration/FlexForms/';

    $plugins = [
        'List',
        'Latest',
        'Teaser',
    ];

    foreach ($plugins as $plugin) {
        $pluginSignature = 'newspage_' . strtolower($plugin);
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            extensionName: $ext,
            pluginName: $plugin,
            pluginTitle: $locallang . ':' . strtolower($plugin) . '.label',
            pluginIcon: 'plugin-newspage',
            group: 'newspage',
            pluginDescription: 'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:newContentWizard.' . strtolower($plugin) . '.description'
        );

        // we use the last parameter to match CType instead of list
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            '',
            $flexformPath . $plugin . '.xml',
            $pluginSignature,
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            // The Teaser plugin does not need storage pages, as it uses the selected news' uids directly
            'pi_flexform,' . ($plugin !== 'Teaser' ?
                'pages;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:pages.ALT.list_formlabel,recursive'
                : ''),
            $pluginSignature,
            'after:header'
        );
    }

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItemGroup(
        'tt_content',
        'CType',
        'newspage',
        'Newspage Plugins',
        'before:lists'
    );
})();
