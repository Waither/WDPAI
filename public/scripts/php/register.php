<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/DBconnect.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/userRegister.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

$register = new UserRegister($name, $email, $password);
if ($register->checkPasswords($confirmPassword)) {
    echo json_encode([
        "success" => false,
        "message" => "Passwords do not match"
    ]);
    exit();
}
$register->register();