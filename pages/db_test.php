<?php
include(__DIR__ . "/db/db.php");

if ($conn && !$conn->connect_error) {
    echo "✅ Database Connected Successfully";
} else {
    echo "❌ Connection Failed: " . $conn->connect_error;
}
?>
