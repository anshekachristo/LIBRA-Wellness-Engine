<?php
// Database credentials
$host     = "localhost";
$user     = "root";
$password = "";
$database = "health_portal";

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("❌ DB Connection failed: " . $conn->connect_error);
}

// Optional: Set UTF-8 encoding
$conn->set_charset("utf8mb4");
?>
