<?php
session_start();
include(__DIR__ . "/db/db.php");

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
  header("Location: ../login.php");
  exit();
}

// Get user info
$email = $_SESSION['email'];
$user_sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$user_id = $user['id'] ?? 0;

// Fetch symptom logs for user
$log_sql = "SELECT l.*, s.title, s.type, s.image, s.audio FROM symptom_logs l 
            JOIN suggestions s ON s.id = l.suggestion_id
            WHERE l.user_id = ? ORDER BY l.created_at DESC";
$stmt = $conn->prepare($log_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Profile - Libra</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f1fdfb; font-family: 'Poppins', sans-serif; }
    .container { margin-top: 40px; }
    .card {
      box-shadow: 0 5px 12px rgba(0,0,0,0.1);
      border-radius: 10px;
      margin-bottom: 20px;
      padding: 20px;
    }
    img.thumb {
      height: 80px;
      width: 100%;
      max-width: 100px;
      border-radius: 8px;
      object-fit: cover;
    }
    audio {
      width: 100%;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<div class="container">
  <h3 class="text-center mb-4">🧾 Your Suggestion History</h3>

  <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="card">
        <div class="row">
          <div class="col-md-2">
            <img src="<?php echo htmlspecialchars($row['image']); ?>" class="thumb" alt="Image">
          </div>
          <div class="col-md-10">
            <h5>
              <?php echo htmlspecialchars($row['title']); ?>
              <span class="badge bg-primary"><?php echo htmlspecialchars($row['type']); ?></span>
            </h5>
            <p><strong>Symptom:</strong> <?php echo htmlspecialchars($row['symptom']); ?></p>
            <small class="text-muted">🕓 <?php echo htmlspecialchars($row['created_at']); ?></small>

            <?php if ($row['type'] === 'Music' && !empty($row['audio'])): ?>
              <audio controls>
                <source src="<?php echo htmlspecialchars($row['audio']); ?>" type="audio/mpeg">
                Your browser does not support audio.
              </audio>
            <?php endif; ?>

            <div class="mt-2">
              <a href="../view_suggestion.php?id=<?php echo $row['suggestion_id']; ?>" class="btn btn-outline-info btn-sm">View Details</a>
            </div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p class="text-center">No history available yet.</p>
  <?php endif; ?>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
