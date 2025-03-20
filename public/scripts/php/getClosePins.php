<?php

// Link database
require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/DBconnect.php';

$query = 'SELECT "ID_place", "name_place", "longitude_place", "latitude_place" FROM tb_place;';
$result = query($query, "Pin");

echo json_encode($result);