<?php
defined('TYPO3_MODE') or die('Access denied.');

$ext = 'B13.Newspage';
$locallang = 'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf';
$flexformPath = 'FILE:EXT:newspage/Configuration/FlexForms/';

$plugin = 'newspage_list';
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin($ext, 'List', $locallang . ':list.label');
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$plugin] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($plugin, $flexformPath . 'List.xml');

$plugin = 'newspage_latest';
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin($ext, 'Latest', $locallang . ':latest.label');
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$plugin] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($plugin, $flexformPath . 'Latest.xml');

$plugin = 'newspage_teaser';
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin($ext, 'Teaser', $locallang . ':teaser.label');
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$plugin] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($plugin, $flexformPath . 'Teaser.xml');
