<?php

class Place {
    private int $id;
    private string $name;
    private string $type;
    private string $company;
    private string $address;
    private float $latitude;
    private float $longitude;
    private float $rating;
    private string $image;

    public function __construct(int $id, string $name, string $type, string $company, string $address, float $latitude, float $longitude, float $rating, string $image) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->company = $company;
        $this->address = $address;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->rating = $rating;
        $this->image = $image;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getCompany(): string {
        return $this->company;
    }

    public function getAddress(): string {
        return $this->address;
    }

    public function getLatitude(): float {
        return $this->latitude;
    }

    public function getLongitude(): float {
        return $this->longitude;
    }

    public function getRating(): float {
        return $this->rating;
    }

    public function getImage(): string {
        return $this->image;
    }

    public function getRatingStars(): string {
        $stars = "<span class='tooltiptext'>{$this->rating}</span>";
        for ($i = 0; $i < 5; $i++) {
            if ($this->rating >= $i + 1) {
                $stars .= "<i class='fas fa-star'></i>";
            }
            elseif ($this->rating >= $i + 0.5) {
                $stars .= "<i class='fas fa-star-half-alt half-star'></i>";
            }
            else {
                $stars .= "<i class='far fa-star text-dark'></i>";
            }
        }
        return $stars;
    }
}