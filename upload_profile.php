<?php
session_start();
include(__DIR__ . "/db/db.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['email'];
    $uploadDir = "uploads/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create uploads folder if not exists
    }

    $fileName = basename($_FILES['profile_pic']['name']);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFilePath)) {
            // Update DB
            $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE email = ?");
            $stmt->bind_param("ss", $targetFilePath, $email);
            if ($stmt->execute()) {
                $_SESSION['profile_pic'] = $targetFilePath;
                $message = "✅ Profile picture uploaded successfully.";
            } else {
                $message = "❌ Database update failed.";
            }
        } else {
            $message = "❌ Failed to upload file.";
        }
    } else {
        $message = "❌ Only JPG, JPEG, PNG & GIF files are allowed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Upload Profile Picture</title>
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="padding: 50px; font-family: Poppins;">
  <div class="container" style="max-width: 500px;">
    <h3>Upload Profile Picture</h3>
    <?php if ($message): ?>
      <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="profile_pic" class="form-label">Choose Image</label>
        <input type="file" name="profile_pic" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Upload</button>
    </form>
    <br>
    <a href="homepage.php">🔙 Back to Homepage</a>
  </div>
</body>
</html>
