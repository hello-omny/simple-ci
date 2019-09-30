<?php

if (file_exists(__DIR__ . "/local/repositories.php")) {
    return require __DIR__ . "/local/repositories.php";
}

use core\git\AbstractAction;

return [
    [
        'name' => 'ruslanzh/truck-advert',
        'type' => AbstractAction::REPOSITORY_TYPE_BITBUCKET,
        'branches' => [
            [
                'name' => 'master',
                'deployPath' => '/www/ci/test-data/prod',
                // 'postHookCmd' => 'your_command',
            ]
        ],
    ],
];

