<?php

if (file_exists(__DIR__ . "/local/config.php")) {
    return require __DIR__ . "/local/config.php";
}

return [
    'debug' => true,
    'phpDir' => '/usr/local/bin/php',
    'repositoriesPath' => '/www/ci/test-data/repos',
];