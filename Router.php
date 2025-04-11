<?php

class Router
{
    private string $requestUri;
    private string $viewDir;
    private array $routes;

    public function __construct(string $requestUri, string $viewDir)
    {
        $this->requestUri = $requestUri;
        $this->viewDir = $viewDir;

        $this->routes = [
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
    }

    public function resolve(): string
    {
        $page = $this->routes[$this->requestUri] ?? 'presets/errors/404.php';
        if (!file_exists(__DIR__ . "{$this->viewDir}{$page}")) {
            $page = 'presets/errors/501.php';
        }
        return "{$this->viewDir}{$page}";
    }
}
