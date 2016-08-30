<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__ . '/../app/config/conf.php';
require __DIR__ . '/../app/bootstrap.php';
require __DIR__ . '/../app/routing.php';

$app->run();
