<?php
session_start();
include("db/db.php");

// Show errors during development
ini_set('display_errors', 1);
error_reporting(E_ALL);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Query to validate user
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user) {
    // Set session values
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['username'] = $user['name'] ?? 'User';
    $_SESSION['profile_pic'] = $user['profile_pic'] ?? 'assets/img/default-avatar.png';

    // Admin redirect
    if ($email === 'admin@libra.com') {
      header("Location: pages/admin/dashboard.php");
      exit();
    }

    // Check if patient info already exists
    $stmt2 = $conn->prepare("SELECT * FROM patient_info WHERE email = ? LIMIT 1");
    $stmt2->bind_param("s", $email);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if ($result2->num_rows > 0) {
      // Already filled, go to homepage
      header("Location: homepage.php");
    } else {
      // First-time user, go to patient form
      header("Location: pages/patient_form.php");
    }
    exit();

  } else {
    $message = "❌ Invalid login credentials.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Libra</title>
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
    body {
      background: #d8f5f2;
      font-family: 'Poppins', sans-serif;
    }
    .container {
      max-width: 400px;
      margin-top: 80px;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #027373;
    }
    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(2, 115, 115, 0.25);
      border-color: #027373;
    }
    .btn-success {
      background-color: #027373;
      border-color: #027373;
    }
    .btn-success:hover {
      background-color: #025252;
    }
  </style>
</head>
<body>
  <div class="container text-center">
    <img src="assets/img/libra-logo.png" alt="Libra Logo" style="height: 80px; margin-bottom: 20px;">
    <h2>🔐 Login</h2>

    <?php if ($message): ?>
      <div class="alert alert-danger"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3 text-start">
        <label for="email" style="color: black;">Email</label>
        <input type="email" name="email" id="email" class="form-control" required>
      </div>
      <div class="mb-3 text-start">
        <label for="password" style="color: black;">Password</label>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-success w-100">Login</button>
      <p class="text-center mt-3" style="color: black;">New user? <a href="register.php">Register here</a></p>
    </form>
  </div>
</body>
</html>
