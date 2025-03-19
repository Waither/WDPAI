<?php

class Pin {
    private int $id;
    private string $name;
    private array $location;

    public function __construct(int $id, string $name, array $location) {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
    }
}