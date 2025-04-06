<?php

class Comment {
    public int $id;
    public string $user;
    public string $place;
    public string $company;
    public string $address;
    public DateTime $date;
    public string $text;
    public float $rating;

    public function __construct(array $data) {
        $this->id = $data['id'];
        $this->user = $data['user'];
        $this->place = $data['place'];
        $this->company = $data['company'];
        $this->address = $data['address'];
        $this->date = new DateTime($data['date']);
        $this->text = $data['text'];
        $this->rating = $data['rating'] / 2;
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