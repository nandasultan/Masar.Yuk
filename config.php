<?php
// config.php - edit DB creds if needed
session_start();
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'masardb';
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    http_response_code(500);
    die("DB Connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
?>