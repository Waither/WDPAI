<?php

class User
{
    public int $ID_user;
    public string $name;
    public ?int $ID_company;
    public string $ID_special;
    public array $roles = [];
    public string $email;

    public function __construct(array $data) {
        $this->ID_user = $data['ID_user'];
        $this->name = $data['name'];
        $this->ID_company = $data['ID_company'] ?? null;
        $this->ID_special = $data['ID_special'];
        $this->roles = $this->parseRoles($data['fcn__getRoles'] ?? '');
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
