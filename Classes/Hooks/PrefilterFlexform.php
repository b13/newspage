<?php

namespace B13\Newspage\Hooks;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class PrefilterFlexform
 * @package B13\Newspage\Hooks
 */
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
                        $dataStructure['sheets']['preFilter']['ROOT']['el']['settings.prefilter.' . strtolower($type)] = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($content);
                    }
                }
            }
        }
        return $dataStructure;
    }
}
