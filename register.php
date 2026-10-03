<?php
include(__DIR__ . "/db/db.php");

// Show errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Handle file upload
  $profilePic = '';
  if (!empty($_FILES['profile_pic']['name'])) {
    $uploadDir = "uploads/";
    $fileName = basename($_FILES["profile_pic"]["name"]);
    $targetFile = $uploadDir . time() . "_" . $fileName;

    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
      $profilePic = basename($targetFile);
    } else {
      $message = "❌ Failed to upload profile picture.";
    }
  }

  // Check if email already exists
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $message = "❗ Email already registered.";
  } else {
    $stmt = $conn->prepare("INSERT INTO users (email, password, profile_pic) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $password, $profilePic);
    if ($stmt->execute()) {
      header("Location: login.php");
      exit();
    } else {
      $message = "❌ Registration failed.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Register - Libra</title>
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #e6fdf8;
      font-family: 'Poppins', sans-serif;
    }
    .container {
      max-width: 420px;
      margin-top: 80px;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #027373;
    }
  </style>
</head>
<body>
  <div class="container text-center">
    <img src="assets/img/libra-logo.png" alt="Libra Logo" style="height: 80px; margin-bottom: 20px;">
    <h2>📝 Register</h2>
    <?php if ($message): ?>
      <div class="alert alert-warning"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3 text-start">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>

      <div class="mb-3 text-start">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>

      <div class="mb-3 text-start">
        <label>Profile Picture</label>
        <input type="file" name="profile_pic" class="form-control" accept="image/*">
      </div>

      <button type="submit" class="btn btn-primary w-100">Register</button>
      <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
    </form>
  </div>
</body>
</html>
