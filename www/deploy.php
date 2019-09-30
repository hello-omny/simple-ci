<?php

use app\Application;

require(__DIR__ . '/../vendor/autoload.php');

$config = require __DIR__ . "/../config/config.php";
$repositories = require __DIR__ . "/../config/repositories.php";

$app = new Application($config, $repositories);

$app->run();