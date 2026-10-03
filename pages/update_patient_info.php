<?php
session_start();
include "../db/db.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];

$age = $_POST['age'] ?? null;
$gender = $_POST['gender'] ?? '';
$height = $_POST['height'] ?? 0;
$weight = $_POST['weight'] ?? 0;
$blood_group = $_POST['blood_group'] ?? '';
$symptoms = $_POST['symptoms'] ?? '';
$allergies = $_POST['allergies'] ?? '';
$existing_conditions = $_POST['existing_conditions'] ?? '';
$medications = $_POST['medications'] ?? '';
$smoking_status = $_POST['smoking_status'] ?? '';
$alcohol_consumption = $_POST['alcohol_consumption'] ?? '';
$activity_level = $_POST['activity_level'] ?? '';
$diet_type = $_POST['diet_type'] ?? '';
$stress_level = $_POST['stress_level'] ?? '';
$sleep_hours = $_POST['sleep_hours'] ?? 0;
$water_intake = $_POST['water_intake'] ?? 0;
$pain_level = $_POST['pain_level'] ?? 0;
$mental_health = $_POST['mental_health'] ?? '';

$sql = "UPDATE patient_info SET
    age=?, gender=?, height=?, weight=?, blood_group=?, symptoms=?, allergies=?, 
    existing_conditions=?, medications=?, smoking_status=?, alcohol_consumption=?,
    activity_level=?, diet_type=?, stress_level=?, sleep_hours=?, water_intake=?, 
    pain_level=?, mental_health=?
WHERE user_id=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "issddsssssssssdidisii",
    $age, $gender, $height, $weight, $blood_group, $symptoms, $allergies,
    $existing_conditions, $medications, $smoking_status, $alcohol_consumption,
    $activity_level, $diet_type, $stress_level, $sleep_hours, $water_intake,
    $pain_level, $mental_health, $user_id
);

if ($stmt->execute()) {
    header("Location: ../pages/view_profile.php");
    exit();
} else {
    echo "Update failed: " . $stmt->error;
}
?>
