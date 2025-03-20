<?php
session_start();

$mail = $_POST['email'];
$pass = $_POST['password'];

require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/DBconnect.php';

$user = json_decode(query('SELECT "ID_special" FROM tb_user NATURAL INNER JOIN tb_login WHERE "email" = :email AND "password" = :password', "User", [":email" => $mail, ":password" => $pass]));
if (!$user) {
    echo json_encode([
        "success" => false,
        "message" => "User not found"
    ]);
    exit();
}

$id = $user[0]->ID_special;

setcookie("user", $id, time() + 86400 * 30 * 365, "/", "", true, true);

echo json_encode([
    "success" => true,
    "id" => $id
]);
