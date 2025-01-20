<?php

declare(strict_types=1);

namespace B13\Newspage\Service;

/*
  * This file is part of TYPO3 CMS-based extension "newspage" by b13.
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 2
  * of the License, or any later version.
  */

use B13\Newspage\Filter\FilterInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FilterService
{
    public static function registerFilter(string $name, string $class, string $label, string $flexFormPath = ''): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newspage']['filters'][$name] = [
            'class' => $class,
            'label' => $label,
            'flexForm' => $flexFormPath,
        ];
    }

    public static function getFiltersForFlexform(array &$config): void
    {
        foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newspage']['filters'] as $filter => $options) {
            $config['items'][] = [
                $options['label'],
                $filter,
            ];
        }
    }

    public static function getFilterOptionsForFluid(string $filter): array
    {
        if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newspage']['filters'][$filter]['class'])) {
            return [];
        }
        if (!class_exists($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newspage']['filters'][$filter]['class'])) {
            throw new \InvalidArgumentException('no such class ' . $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newspage']['filters'][$filter]['class'], 1693396989);
        }
        /** @var FilterInterface $filterObj */
        $filterObj = GeneralUtility::makeInstance($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newspage']['filters'][$filter]['class']);
        if (!$filterObj instanceof FilterInterface) {
            throw new \InvalidArgumentException($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newspage']['filters'][$filter]['class'] . ' must implement FilterInterface', 1693396990);
        }
        return $filterObj->getItems();
    }
}
