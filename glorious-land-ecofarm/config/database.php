<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "glorious_land_ecofarm";

$conn = new mysqli(
    $host,
    $username,
    $password,
    $database
);

if ($conn->connect_error) {
    die(
        "Database connection failed.<br>" .
        "Error: " . $conn->connect_error
    );
}

if (!$conn->set_charset("utf8mb4")) {
    die(
        "Database character set failed.<br>" .
        "Error: " . $conn->error
    );
}

?>