<?php

class User
{
    public int $ID_user;
    public string $name;
    public ?string $company;
    public ?string $nationality;
    public string $ID_special;
    public array $roles;
    public string $email;

    public function __construct(array $data) {
        $this->ID_user = $data['ID_user'];
        $this->name = $data['name'];
        $this->company = $data['company'] ?? null;
        $this->nationality = $data['nationality'] ?? null;
        $this->ID_special = $data['ID_special'];
        $this->roles = $this->parseRoles($data['roles'] ?? '');
        if (isset($data['email'])) {
            $this->email = $data['email'];
        }
    }

    private function parseRoles(string $raw): array {
        return str_getcsv(trim($raw, '{}'));
    }

    public function getRoles(): array{
        return $this->roles;
    }
}
