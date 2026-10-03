<?php
include(__DIR__ . "/../../db/db.php");
session_start();

// ✅ Only admin can delete
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@libra.com') {
    header("Location: ../../login.php");
    exit;
}

// ✅ Get the suggestion ID from URL
$id = $_GET['id'] ?? null;
if (!$id) {
    die("❌ Invalid suggestion ID.");
}

// ✅ Get image/audio file paths before deletion
$stmt = $conn->prepare("SELECT image, audio FROM suggestions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$fileData = $result->fetch_assoc();

// ✅ Delete media files if they exist
if ($fileData) {
    if (!empty($fileData['image']) && file_exists("../../" . $fileData['image'])) {
        unlink("../../" . $fileData['image']);
    }
    if (!empty($fileData['audio']) && file_exists("../../" . $fileData['audio'])) {
        unlink("../../" . $fileData['audio']);
    }
}

// ✅ Delete the suggestion from database
$delete = $conn->prepare("DELETE FROM suggestions WHERE id = ?");
$delete->bind_param("i", $id);
$delete->execute();

// ✅ Redirect to dashboard with status
header("Location: dashboard.php?deleted=1");
exit;
?>
