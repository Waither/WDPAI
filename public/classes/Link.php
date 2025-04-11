<?php

class Link {
    public string $name;
    public string $icon;
    public string $url;
    public bool $logged;
    public string $role;

    public function __construct(string $name, string $icon, string $url, bool $logged = false, string $role = "") {
        $this->name = $name;
        $this->icon = $icon;
        $this->url = $url;
        $this->logged = $logged;
        $this->role = $role;
    }
}
