<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/DBconnect.php';

try {
    $result = query('CALL truckstop."prc__updateUserRoles"(:user, :ids);', $_POST);

    if (is_string($result)) {
        throw new Exception($result);
    }

    if (empty($result)) {
        echo json_encode([
            "success" => true,
            "message" => "Roles updated successfully."
        ]);
        exit();
    }
}
catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
    exit();
}