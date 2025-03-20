<?php

class Place {
    private int $id;
    private string $name;
    private string $type;
    private string $company;
    private string $address;
    private array $location;
    private float $rating;
    private string $image;

    public function __construct(int $id, string $name, string $type, string $company, string $description, string $address, array $location, float $rating, string $image) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->company = $company;
        $this->address = $address;
        $this->location = $location;
        $this->rating = $rating;
        $this->image = $image;
    }
}