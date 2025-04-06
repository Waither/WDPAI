<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/DBconnect.php';

$userID = $_GET['userID'];
try {
    $user = query('SELECT * FROM vw_user WHERE "ID_special" = :userID;', [':userID' => $userID], "User");

    if (!is_array($user)) {
        throw new Exception($user);
    }
    
    echo json_encode([
        "success" => true,
        "data" => $user[0]
    ]);
}
catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
    exit();
}