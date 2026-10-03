<?php
session_start();
include(__DIR__ . "/db/db.php");

echo "<h2>✅ SESSION Check:</h2><pre>";
print_r($_SESSION);
echo "</pre>";

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    $stmt = $conn->prepare("SELECT * FROM patient_info WHERE user_id = ? LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $info = $result->fetch_assoc();

    echo "<h2>✅ PATIENT INFO Check:</h2><pre>";
    print_r($info);
    echo "</pre>";
} else {
    echo "<p>❌ No user_id found in session.</p>";
}
?>
