<?php

class User {
    public int $id;
    public string $name;
    public string $email;
    public $password;
    public array $roles;
    public string $id_special;

    public function __construct(int $ID, string $name, string $email, string $password, array $roles, string $id_special) {
        $this->id = $ID;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;
        $this->id_special = $id_special;
    }
}