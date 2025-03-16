<?php

class Place {
    private int $id;
    private string $name;
    private string $type;
    private string $company;
    private string $description;
    private string $address;
    private float $latitude;
    private float $longitude;
    private float $rating;
    private string $image;

    public function __construct(int $id, string $name, string $type, string $company, string $description, string $address, float $latitude, float $longitude, float $rating, string $image) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->company = $company;
        $this->description = $description;
        $this->address = $address;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->rating = $rating;
        $this->image = $image;
    }
}