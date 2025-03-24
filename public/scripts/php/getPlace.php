<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/DBconnect.php';

$placeID = $_GET['placeID'];
try {
    $place = query('SELECT * FROM "vw_place" WHERE "ID_place" = :placeID;', [':placeID' => $placeID], "Place");

    if (!is_array($place)) {
        throw new Exception($place);
    }

    $place = (object)$place[0];

    echo json_encode([
        "success" => true,
        "data" => [
            "ID_place" => $place->id,
            "name" => $place->name,
            "placeTags" => $place->placeTags ? explode(',', $place->placeTags) : [],
            "type" => $place->type ? explode(',', $place->type) : [],
            "rating" => $place->getRatingStars(),
            "company" => $place->company,
            "address" => $place->address,
            "latitude" => $place->latitude,
            "longitude" => $place->longitude,
            "image" => $place->image
        ]
    ]);
}
catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
    exit();
}