<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/DBconnect.php';

try {
    $user = query('DELETE FROM "tb_user" WHERE "ID_user" = :user', $_POST);

    if (is_string($user)) {
        throw new UnexpectedValueException($user);
    }
    
    echo json_encode([
        "success" => true,
        "message" => "User deleted successfully."
    ]);

}
catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
    exit();
}
