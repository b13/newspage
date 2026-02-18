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
            $ext,
            $plugin,
            $locallang . ':' . strtolower($plugin) . '.label',
            'plugin-newspage',
            'newspage',
            'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:newContentWizard.' . strtolower($plugin) . '.description'
        );
        $GLOBALS['TCA']['tt_content']['types'][$pluginSignature] = $GLOBALS['TCA']['tt_content']['types']['header']; // TODO: why this type?
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            'pi_flexform,
        pages;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:pages.ALT.list_formlabel,
        recursive',
            $pluginSignature,
            'after:header'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, $flexformPath . $plugin . '.xml');
    }

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItemGroup(
        'tt_content',
        'CType',
        'newspage',
        'Newspage Plugins',
        'before:lists'
    );
})();
