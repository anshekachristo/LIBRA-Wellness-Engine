<?php
session_start();
include(__DIR__ . '/../db/db.php'); // secure path

// Get logged-in user's ID from session
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die("Error: User not logged in");
}

// Use profile_pic from session (uploaded during registration)
$profile_pic = $_SESSION['profile_pic'] ?? 'default.png';

// Fetch form data
$name = $_POST['name'];
$email = $_POST['email'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$blood_group = $_POST['blood_group'];
$height = $_POST['height'];
$weight = $_POST['weight'];
$symptom = $_POST['symptom'];
$address = $_POST['address'];
$medications = $_POST['medications'];
$temperature = $_POST['temperature'];
$blood_pressure = $_POST['bp'];
$sugar_level = $_POST['sugar'];
$medical_note = $_POST['medical_history'];

// Insert into patient_info with user_id
$sql = "INSERT INTO patient_info (
    user_id, name, email, age, gender, blood_group, height, weight, symptom, address, medications,
    temperature, bp, sugar, medical_history, profile_pic
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ississddssssssss",
    $user_id, $name, $email, $age, $gender, $blood_group, $height, $weight, $symptom, $address,
    $medications, $temperature, $blood_pressure, $sugar_level, $medical_note, $profile_pic
);

if ($stmt->execute()) {
    header("Location: ../homepage.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
