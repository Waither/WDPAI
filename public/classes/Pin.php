<?php

class Pin {
    public int $id;
    public string $name;
    public array $location;

    public function __construct(int $id, string $name, array $location) {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
    }
}
