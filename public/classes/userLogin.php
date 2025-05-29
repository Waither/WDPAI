<?php

class UserLogin {
    private $email;
    private $password;

    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
    }

    public function login() {
        try {
            $user = query('SELECT truckstop."fcn__loginUser"(:email, :password) AS "uuid"', [
                ":email" => $this->email,
                ":password" => $this->password
            ]);

            if (!is_array($user)) {
                throw new UnexpectedValueException("incorrect email or password");
            }

            $user = (object)$user[0];

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
}
