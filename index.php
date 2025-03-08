<?php
// preg_match('/mobile|android|iphone|ipad|phone/i', $userAgent ?? $_SERVER['HTTP_USER_AGENT']) ? 'true' : 'false';

$request = $_SERVER['REQUEST_URI'];
$viewDir = '/public/views/';

$routes = [
    '/' => 'home.html',
    '/places' => 'places.html',
    '/map' => 'map.html',
    '/'
];

$page = $routes[$request] ?? '404.html';
if (!file_exists(__DIR__ . "{$viewDir}{$page}")) {
    $page = '501.html';
}

require __DIR__ . "{$viewDir}{$page}";