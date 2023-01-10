<?php
$host     = "localhost"; // Database Host
$user     = "zenterin_suser"; // Database Username
$password = "c0sm1cp0w3r"; // Database's user Password
$database = "zenterin_s"; // Database Name

$mysqli = new mysqli($host, $user, $password, $database);

// Checking Connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$mysqli->set_charset("utf8mb4");

// Settings
include "config_settings.php";
?>