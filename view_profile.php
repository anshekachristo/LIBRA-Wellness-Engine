<?php
session_start();
include(__DIR__ . "/db/db.php");

// Enable error reporting (for development)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check login
if (!isset($_SESSION['user_id'])) {
    echo "Please login to view your profile.";
    exit();
}

// Get patient info
$stmt = $conn->prepare("SELECT * FROM patient_info WHERE user_id = ? LIMIT 1");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Patient info not found.";
    exit();
}

$patient = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Profile - Libra</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
   <style>
    body {
        background: linear-gradient(to right, #e0f7f5, #e8fbff);
        font-family: 'Poppins', sans-serif;
        color: #222;
    }

    .profile-container {
        max-width: 900px;
        margin: 60px auto;
        background: #fff;
        padding: 40px;
        border-radius: 18px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .profile-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .profile-header img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #27b6b2;
        object-fit: cover;
    }

    .profile-header h2 {
        margin-top: 15px;
        font-weight: 600;
        color: #027373;
    }

    .profile-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    .profile-group label {
        font-weight: 600;
        color: #027373;
        margin-bottom: 5px;
        display: block;
    }

    .profile-group p {
        background: #f8fdfc;
        padding: 12px 15px;
        border-radius: 8px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
        color: #333;
        font-size: 15px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .profile-info {
            grid-template-columns: 1fr;
        }
    }
</style>

</head>
<body>

<div class="profile-container">
    <div class="profile-header">
        <img src="uploads/<?= htmlspecialchars($patient['profile_pic']) ?>" alt="Profile Picture">
        <h2><?= htmlspecialchars($patient['name']) ?>'s Profile</h2>
    </div>

    <div class="profile-info">
        <div class="profile-group">
            <label>Email</label>
            <p><?= htmlspecialchars($patient['email']) ?></p>

            <label>Age</label>
            <p><?= htmlspecialchars($patient['age']) ?></p>

            <label>Gender</label>
            <p><?= htmlspecialchars($patient['gender']) ?></p>

            <label>Blood Group</label>
            <p><?= htmlspecialchars($patient['blood_group']) ?></p>

            <label>Height (cm)</label>
            <p><?= htmlspecialchars($patient['height']) ?></p>

            <label>Weight (kg)</label>
            <p><?= htmlspecialchars($patient['weight']) ?></p>

            <label>Symptom</label>
            <p><?= htmlspecialchars($patient['symptom']) ?></p>
        </div>

        <div class="profile-group">
            <label>Address</label>
            <p><?= htmlspecialchars($patient['address']) ?></p>

            <label>Medications</label>
            <p><?= htmlspecialchars($patient['medications']) ?></p>

            <label>Temperature (°F)</label>
            <p><?= htmlspecialchars($patient['temperature']) ?></p>

            <label>Blood Pressure</label>
            <p><?= htmlspecialchars($patient['bp']) ?></p>

            <label>Sugar Level</label>
            <p><?= htmlspecialchars($patient['sugar']) ?></p>

            <label>Medical History</label>
            <p><?= htmlspecialchars($patient['medical_history']) ?></p>
        </div>
    </div>
</div>

</body>
</html>
