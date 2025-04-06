<?php

class Comment {
    public int $id;
    public string $user;
    public string $place;
    public string $company;
    public DateTime $date;
    public string $text;
    public int $rating;

    public function __construct(array $data) {
        $this->id = $data['id'];
        $this->user = $data['user'];
        $this->place = $data['place'];
        $this->company = $data['company'];
        $this->date = new DateTime($data['date']);
        $this->text = $data['text'];
        $this->rating = $data['rating'];
    }
}