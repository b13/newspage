<?php

namespace B13\Newspage\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class FilterService
{
    public static function registerFilter(string $name, string $class, string $label, string $flexFormPath = ''): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newspage']['filters'][$name] = [
            'class' => $class,
            'label' => $label,
            'flexForm' => $flexFormPath
        ];
    }

    public static function getFiltersForFlexform(array &$config): void
    {
        foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newspage']['filters'] as $filter => $options) {
            $config['items'][] = [
                $options['label'],
                $filter
            ];
        };
    }

    public static function getFilterOptionsForFluid(string $filter): array
    {
        $filterObj = GeneralUtility::makeInstance(ObjectManager::class)
            ->get($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newspage']['filters'][$filter]['class']);

        return call_user_func([$filterObj, 'getItems']);
    }
}
