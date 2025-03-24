<?php

class Place {
    public int $id;
    public string $name;
    public string $type;
    public string $placeTags;
    public string $company;
    public string $address;
    public float $latitude;
    public float $longitude;
    public float $rating;
    public string $image;

    public function __construct(int $id, string $name, string $type, string $placeTags, string $company, string $address, float $latitude, float $longitude, float $rating, string $image) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->placeTags = $placeTags;
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

    public function getPlaceTags(): string {
        return $this->placeTags;
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
        $stars = "<div class='cardStars mt-1 tooltip'><span class='tooltiptext'>{$this->rating}</span>";
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
        $stars .= "</div>";
        return $stars;
    }
}