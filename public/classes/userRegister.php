<?php

class UserRegister {
    private $name;
    private $email;
    private $password;

    public function __construct($name, $email, $password) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function register(): void {
        try {
            $user = (object)query('SELECT * FROM truckstop."fcn_registerUser_wrapper"(:name, :email, :password) AS uuid', [
                ":name" => $this->name,
                ":email" => $this->email,
                ":password" => $this->password
            ])[0];

            if ($user->uuid === null) {
                throw new Exception($user->message);
            }

            $id = $user->uuid;

            setcookie("user", $id, time() + 86400 * 30 * 365, "/", "", true, true);

            echo json_encode([
                "success" => true,
                "id" => $id
            ]);
        }
        catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
            exit();
        }
    }

    public function checkPasswords($password): bool {
        return $password !== $this->password;
    }
}