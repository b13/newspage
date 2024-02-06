<?php

return [
    'newspage_layout_edit' => [
        'path' => '/newspage-layout-edit',
        'access' => 'public',
        'target' => \B13\Newspage\Controller\LayoutModuleEditController::class . '::handle',
    ],
];
