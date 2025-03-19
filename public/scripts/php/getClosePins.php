<?php

// Link database
require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/DBconnect.php';

// Check current schema
$schemaQuery = 'SELECT current_schema();';
$schemaResult = query($schemaQuery, "Schema");

echo "Current schema: " . json_encode($schemaResult) . "\n";

$query = 'SELECT * FROM tb_place;';
$result = query($query, "Pin");

echo json_encode($result);