<?php

require_once __DIR__.'/Router.php';
require_once __DIR__.'/Application.php';

$router = new Router($_SERVER['REQUEST_URI'], '/public/views/');
$app = new Application($router);
$app->run();
