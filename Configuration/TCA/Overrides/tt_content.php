<?php
defined('TYPO3_MODE') or die('Access denied.');

(function(){
    $ext = 'B13.Newspage';
    $locallang = 'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf';
    $flexformPath = 'FILE:EXT:newspage/Configuration/FlexForms/';

    $plugins = [
        'List',
        'Latest',
        'Teaser'
    ];

    foreach ($plugins as $plugin) {
        $pluginSignature = 'newspage_' . strtolower($plugin);
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            $ext,
            $plugin,
            $locallang . ':' . strtolower($plugin) . '.label',
            'EXT:newspage/Resources/Public/Icons/Extension.svg'
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
        $GLOBALS['TCA']['tt_content']['columns']['pi_flexform']['config']['ds'][',' . $pluginSignature] =
            $flexformPath . $plugin . '.xml';
    }
})();
