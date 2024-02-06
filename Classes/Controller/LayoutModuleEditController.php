<?php

declare(strict_types=1);

namespace B13\Newspage\Controller;

/*
 * This file is part of TYPO3 CMS-based extension "newspage" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Attribute\Controller as BackendController;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;

#[BackendController]
class LayoutModuleEditController
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody()['data'] ?? [];
        $cmd = $request->getParsedBody()['cmd'] ?? [];

        if(!empty($data)  || !empty($cmd)) {
            /** @var DataHandler $dataHandler */
            $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
            $dataHandler->start($data, $cmd);
            $dataHandler->process_datamap();
            $dataHandler->process_cmdmap();
        }

        return new RedirectResponse($request->getQueryParams()['returnUrl']);
    }
}
