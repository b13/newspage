<?php

declare(strict_types=1);

namespace B13\Newspage\EventListener;

/*
 * This file is part of TYPO3 CMS-based extension "newspage" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use TYPO3\CMS\Backend\Controller\Event\ModifyPageLayoutContentEvent;
use TYPO3\CMS\Backend\Form\FormDataCompiler;
use TYPO3\CMS\Backend\Form\FormDataGroup\TcaDatabaseRecord;
use TYPO3\CMS\Backend\Form\FormResultCompiler;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\WorkspaceRestriction;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Type\Bitmask\Permission;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class NewsLayoutListener
{
    public const DOKTYPE_NEWSPAGE = 24;

    public function __construct(
        protected readonly PageRenderer $pageRenderer,
        protected readonly IconFactory $iconFactory,
        protected readonly ExtensionConfiguration $extensionConfiguration,
        protected readonly NodeFactory $nodeFactory,
        protected readonly UriBuilder $uriBuilder,
    ) {}

    public function __invoke(ModifyPageLayoutContentEvent $event): void
    {
        if (!$this->extensionConfiguration->get('newspage', 'layout_edit_mode')) {
            return;
        }

        $request = $event->getRequest();
        $queryParams = $request->getQueryParams();
        $pageId = (int)($queryParams['id'] ?? 0);
        $language = (int)($queryParams['language'] ?? 0);
        $function = (int)($queryParams['function'] ?? 1);
        $pageInfo = BackendUtility::readPageAccess($pageId, $this->getBackendUser()->getPagePermsClause(Permission::PAGE_SHOW));

        // Display page property inline edit only for doktype=24 and function=1 (layout mode)
        if ($function !== 1 || $pageInfo['doktype'] !== self::DOKTYPE_NEWSPAGE || !$this->isPageEditable($language, $pageInfo)) {
            return;
        }

        $formResultCompiler = GeneralUtility::makeInstance(FormResultCompiler::class);
        $formDataGroup = GeneralUtility::makeInstance(TcaDatabaseRecord::class);

        if ($language > 0) {
            $overlayRecord = $this->getLocalizedPageRecord($language, $pageId);
            if ($overlayRecord === null) {
                return;
            }

            $pageId = $overlayRecord['uid'];
        }

        $formDataCompiler = GeneralUtility::makeInstance(FormDataCompiler::class);
        $formDataCompilerInput = [
            'tableName' => 'pages',
            'command' => 'edit',
            'vanillaUid' => $pageId,
            'request' => $request,
        ];

        // Render only the palette "tx_newspage_layout" in page layout view:
        $GLOBALS['TCA']['pages']['types'][self::DOKTYPE_NEWSPAGE]['showitem'] = '--palette--;;tx_newspage_layout';

        $formData = $formDataCompiler->compile($formDataCompilerInput, $formDataGroup);
        $formData['renderType'] = 'fullRecordContainer';

        $formResult = $this->nodeFactory->create($formData)->render();
        $formResultCompiler->mergeResult($formResult);
        $formResultCompiler->printNeededJSFunctions();

        $editUri = (string)$this->uriBuilder->buildUriFromRoute('tce_db', [
            'edit' => [
                'pages' => [
                    $pageId => 'edit',
                ],
            ],
            'redirect' => $event->getRequest()->getAttribute('normalizedParams')->getRequestUri(),
        ]);

        $this->registerDocHeaderButtons($event->getModuleTemplate());

        $formContent = '
            <form
                class="mb-4"
                action="' . htmlspecialchars($editUri) . '"
                method="post"
                enctype="multipart/form-data"
                name="editform"
                id="EditDocumentController"
            >
            ' . $formResult['html'] . '
            <input type="hidden" name="closeDoc" value="1" />
            <input type="hidden" name="doSave" value="1" />
            </form>';

        // Disable inline editing of the title because it conflicts with the slug field regenerate button
        $this->pageRenderer->addJsFooterInlineCode('title-edit-inline-disable', '
            document.querySelector("typo3-backend-editable-page-title").removeAttribute("editable")
        ');

        $event->addHeaderContent($formContent);
    }

    protected function getLocalizedPageRecord(int $languageId, int $pageId): ?array
    {
        if ($languageId === 0) {
            return null;
        }
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $queryBuilder->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class))
            ->add(GeneralUtility::makeInstance(WorkspaceRestriction::class, $this->getBackendUser()->workspace));
        $overlayRecord = $queryBuilder
            ->select('*')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq(
                    $GLOBALS['TCA']['pages']['ctrl']['transOrigPointerField'],
                    $queryBuilder->createNamedParameter($pageId, Connection::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    $GLOBALS['TCA']['pages']['ctrl']['languageField'],
                    $queryBuilder->createNamedParameter($languageId, Connection::PARAM_INT)
                )
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();
        if ($overlayRecord) {
            BackendUtility::workspaceOL('pages', $overlayRecord, $this->getBackendUser()->workspace);
        }
        return is_array($overlayRecord) ? $overlayRecord : null;
    }

    /**
     * Check if page can be edited by current user.
     */
    protected function isPageEditable(int $languageId, $pageInfo): bool
    {
        if ($GLOBALS['TCA']['pages']['ctrl']['readOnly'] ?? false) {
            return false;
        }
        $backendUser = $this->getBackendUser();
        if ($backendUser->isAdmin()) {
            return true;
        }
        if ($GLOBALS['TCA']['pages']['ctrl']['adminOnly'] ?? false) {
            return false;
        }
        return is_array($pageInfo)
            && $pageInfo !== []
            && !(bool)($pageInfo[$GLOBALS['TCA']['pages']['ctrl']['editlock'] ?? null] ?? false)
            && $backendUser->doesUserHaveAccess($pageInfo, Permission::PAGE_EDIT)
            && $backendUser->checkLanguageAccess($languageId)
            && $backendUser->check('tables_modify', 'pages');
    }

    protected function registerDocHeaderButtons(ModuleTemplate $view): void
    {
        $buttonBar = $view->getDocHeaderComponent()->getButtonBar();
        $button = $buttonBar
            ->makeInputButton()
            ->setName('_savedok')
            ->setValue('1')
            ->setTitle($this->getLanguageService()->sL('LLL:EXT:newspage/Resources/Private/Language/locallang_be.xlf:layout.button.save'))
            ->setShowLabelText(true)
            ->setIcon($this->iconFactory->getIcon('actions-save', Icon::SIZE_SMALL))
            ->setForm('EditDocumentController');

        $buttonBar->addButton(
            $button,
            ButtonBar::BUTTON_POSITION_LEFT,
            10
        );
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
