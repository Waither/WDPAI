<?php

$request = $_SERVER['REQUEST_URI'];
$viewDir = '/public/views/';

$routes = [
    '/' => 'home.php',
    '/places' => 'places.php',
    '/map' => 'map.php',
    '/login' => 'login.php',
    '/register' => 'register.php',
    '/user' => 'user.php',
    '/admin' => 'admin.php',
    '/moderator' => 'moderator.php',
];

$page = $routes[$request] ?? 'presets/errors/404.html';
if (!file_exists(__DIR__."{$viewDir}{$page}")) {
    $page = 'presets/errors/501.html';
}

require __DIR__."{$viewDir}{$page}";