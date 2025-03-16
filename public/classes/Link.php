<?php

class Link {
    public $name;
    public $icon;
    public $url;
    public $always;
    public $logged;
    public $roles;

    public function __construct(string $name, string $icon, string $url, bool $always = true, bool $logged = false, array $roles = []) {
        $this->name = $name;
        $this->icon = $icon;
        $this->url = $url;
        $this->always = $always;
        $this->logged = $logged;
        $this->roles = $roles;
    }
}