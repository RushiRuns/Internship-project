<?php
$conn = new mysqli('localhost', 'root', '', 'authentication');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Set charset to avoid issues with special characters
$conn->set_charset("utf8mb4");
?>