<?php

namespace B13\Newspage\EventListener;

/*
  * This file is part of TYPO3 CMS-based extension "newspage" by b13.
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 2
  * of the License, or any later version.
  */

use TYPO3\CMS\Core\Configuration\Event\AfterFlexFormDataStructureParsedEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class FlexFormPreFilterProvider
{

    public function __invoke(AfterFlexFormDataStructureParsedEvent $event): void
    {
        $identifier = $event->getIdentifier();
        if ($identifier['type'] === 'tca' && $identifier['tableName'] === 'tt_content' && $identifier['dataStructureKey'] === ',newspage_list') {
            $dataStructure = $event->getDataStructure();
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newspage']['filters'] as $type => $filter) {
                if ($filter['flexForm'] !== '') {
                    $file = GeneralUtility::getFileAbsFileName($filter['flexForm']);
                    $content = file_get_contents($file);
                    if ($content) {
                        $dataStructure['sheets']['preFilters']['ROOT']['el']['settings.prefilters.' . strtolower($type)] = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($content);
                    }
                }
            }
            $event->setDataStructure($dataStructure);
        }
    }
}
