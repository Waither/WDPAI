<?php
// preg_match('/mobile|android|iphone|ipad|phone/i', $userAgent ?? $_SERVER['HTTP_USER_AGENT']) ? 'true' : 'false';

$request = $_SERVER['REQUEST_URI'];
$viewDir = '/public/views/';

$routes = [
    '/' => 'home.php',
    '/places' => 'places.php',
    '/map' => 'map.php',
    '/'
];

$page = $routes[$request] ?? 'presets/404.html';
if (!file_exists(__DIR__."{$viewDir}{$page}")) {
    $page = 'presets/501.html';
}

require __DIR__."{$viewDir}{$page}";