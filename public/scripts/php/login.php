<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/DBconnect.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/userLogin.php';

$mail = $_POST['email'];
$pass = $_POST['password'];

$login = new UserLogin($mail, $pass);
$login->login();