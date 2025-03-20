<?php

class Link {
    public $name;
    public $icon;
    public $url;
    public $logged;
    public $roles;

    public function __construct(string $name, string $icon, string $url, bool $logged = false, array $roles = []) {
        $this->name = $name;
        $this->icon = $icon;
        $this->url = $url;
        $this->logged = $logged;
        $this->roles = $roles;
    }
}