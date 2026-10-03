<?php
session_start();
include(__DIR__ . "/db/db.php");

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM suggestions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result->num_rows) {
    echo "No suggestion found.";
    exit();
}

$suggestion = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($suggestion['title']) ?> - Libra Suggestion</title>
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/aos.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to bottom right, #e6fffd, #f0ffff);
      font-family: 'Poppins', sans-serif;
      padding: 50px 20px;
    }
    .card-box {
      max-width: 800px;
      margin: auto;
      background: white;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }
    .card-box img {
      max-height: 250px;
      object-fit: contain;
      margin-bottom: 25px;
      width: 100%;
      border-radius: 12px;
    }
    .card-box audio {
      width: 100%;
      margin-top: 20px;
    }
    h2 {
      color: #027373;
      font-weight: bold;
      margin-bottom: 20px;
    }
    .btn-back {
      margin-top: 30px;
      background: #027373;
      color: white;
      border-radius: 50px;
      padding: 8px 20px;
      text-decoration: none;
      display: inline-block;
    }
    .btn-back:hover {
      background: #025252;
      color: white;
    }
  </style>
</head>
<body>

<div class="card-box" data-aos="fade-up">
  <h2><?= htmlspecialchars($suggestion['title']) ?></h2>

  <?php if ($suggestion['type'] === 'Music'): ?>
    <img src="assets/img/music-icon.png" alt="Music Icon">
    <p><?= htmlspecialchars($suggestion['description']) ?></p>
    <?php if (!empty($suggestion['audio'])): ?>
      <audio controls>
        <source src="<?= $suggestion['audio'] ?>" type="audio/mpeg">
        Your browser does not support audio playback.
      </audio>
    <?php else: ?>
      <p><em>No music file available.</em></p>
    <?php endif; ?>
  <?php else: ?>
    <?php if (!empty($suggestion['image'])): ?>
      <img src="<?= $suggestion['image'] ?>" alt="<?= htmlspecialchars($suggestion['title']) ?>">
    <?php endif; ?>
    <p><?= htmlspecialchars($suggestion['description']) ?></p>
  <?php endif; ?>

  <a href="homepage.php" class="btn-back">← Back to Homepage</a>
</div>

<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/aos.js"></script>
<script>
  AOS.init();
</script>

</body>
</html>
