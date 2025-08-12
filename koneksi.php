<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'insect_db');

$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($mysqli === false){
    die("ERROR: Tidak dapat terhubung. " . $mysqli->connect_error);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>