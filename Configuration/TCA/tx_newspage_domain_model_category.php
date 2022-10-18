<?php
defined('TYPO3_MODE') or die('Access denied!');

return [
    'ctrl' => [
        'title' => 'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:category',
        'label' => 'name',
        'iconfile' => 'EXT:newspage/Resources/Public/Icons/Extension.svg',
        'sortby' => 'sorting',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
    ],
    'columns' => [
        'name' => [
            'label' => 'LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:category.name',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ]
        ]
    ],
    'types' => [
        '0' => ['showitem' => 'name']
    ]
];
