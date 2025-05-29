<?php

// Link database
require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/DBconnect.php';

$position = $_GET['position'];
try {
    $pins = query('SELECT "ID_place", "name_place", "longitude_place", "latitude_place" FROM tb_place;', [],"Pin");

    if (!is_array($pins)) {
        throw new UnexpectedValueException($pins);
    }

    foreach ($pins as &$pin) {
        $pin = (object)$pin;
    }

    echo json_encode([
        "success" => true,
        "data" => $pins
    ]);
}
catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
    exit();
}
