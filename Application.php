<?php

class Application
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function run(): void
    {
        $page = $this->router->resolve();
        require __DIR__ . $page;
    }
}