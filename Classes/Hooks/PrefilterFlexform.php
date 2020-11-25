<?php

namespace B13\Newspage\Hooks;

/*
  * This file is part of TYPO3 CMS-based extension "newspage" by b13.
  *
  * It is free software; you can redistribute it and/or modify it under
  * the terms of the GNU General Public License, either version 2
  * of the License, or any later version.
  */

use TYPO3\CMS\Core\Utility\GeneralUtility;

class PrefilterFlexform
{

    /**
     * @param array $dataStructure
     * @param array $identifier
     * @return array
     */
    public function parseDataStructureByIdentifierPostProcess(array $dataStructure, array $identifier): array
    {
        // TODO: why is it ",newspage_list" ?
        if ($identifier['type'] === 'tca' && $identifier['tableName'] === 'tt_content' && $identifier['dataStructureKey'] === ',newspage_list') {

            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['newspage']['filters'] as $type => $filter) {
                if ($filter['flexForm'] !== '') {
                    $file = GeneralUtility::getFileAbsFileName($filter['flexForm']);
                    $content = file_get_contents($file);
                    if ($content) {
                        $dataStructure['sheets']['preFilters']['ROOT']['el']['settings.prefilters.' . strtolower($type)] = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($content);
                    }
                }
            }
        }
        return $dataStructure;
    }
}
