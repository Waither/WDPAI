<?php

$request = $_SERVER['REQUEST_URI'];
$viewDir = '/public/views/';

$routes = [
    '/' => 'home.php',
    '/places' => 'places.php',
    '/map' => 'map.php',
    '/favourite' => 'favourite.php',
    '/login' => 'login.php',
    '/register' => 'register.php',
    '/user' => 'user.php',
    '/admin' => 'admin.php',
    '/moderator' => 'moderator.php',
];

$page = $routes[$request] ?? 'presets/errors/501.php';
if (!file_exists(__DIR__."{$viewDir}{$page}")) {
    $page = 'presets/errors/404.php';
}

require __DIR__."{$viewDir}{$page}";