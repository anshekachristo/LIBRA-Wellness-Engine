<?php
session_start();
include(__DIR__ . "/db/db.php");

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM patient_info WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("No patient info found. Please submit the form first.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Patient Info - Libra</title>
</head>
<body>
    <h2>Edit Patient Information</h2>
    <form action="update_patient_info.php" method="POST">
        Age: <input type="number" name="age" value="<?= $data['age'] ?>"><br>
        Gender: <input type="text" name="gender" value="<?= $data['gender'] ?>"><br>
        Height: <input type="text" name="height" value="<?= $data['height'] ?>"><br>
        Weight: <input type="text" name="weight" value="<?= $data['weight'] ?>"><br>
        Blood Group: <input type="text" name="blood_group" value="<?= $data['blood_group'] ?>"><br>
        Symptoms: <textarea name="symptoms"><?= $data['symptoms'] ?></textarea><br>
        Allergies: <textarea name="allergies"><?= $data['allergies'] ?></textarea><br>
        Existing Conditions: <textarea name="existing_conditions"><?= $data['existing_conditions'] ?></textarea><br>
        Medications: <textarea name="medications"><?= $data['medications'] ?></textarea><br>
        Smoking Status: <input type="text" name="smoking_status" value="<?= $data['smoking_status'] ?>"><br>
        Alcohol Consumption: <input type="text" name="alcohol_consumption" value="<?= $data['alcohol_consumption'] ?>"><br>
        Activity Level: <input type="text" name="activity_level" value="<?= $data['activity_level'] ?>"><br>
        Diet Type: <input type="text" name="diet_type" value="<?= $data['diet_type'] ?>"><br>
        Stress Level: <input type="text" name="stress_level" value="<?= $data['stress_level'] ?>"><br>
        Sleep Hours: <input type="number" name="sleep_hours" value="<?= $data['sleep_hours'] ?>"><br>
        Water Intake: <input type="number" name="water_intake" value="<?= $data['water_intake'] ?>"><br>
        Pain Level: <input type="number" name="pain_level" value="<?= $data['pain_level'] ?>"><br>
        Mental Health: <textarea name="mental_health"><?= $data['mental_health'] ?></textarea><br>
        
        <button type="submit">Update Info</button>
    </form>
</body>
</html>
