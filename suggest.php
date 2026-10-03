<?php
session_start();
include("db/db.php"); // adjust path if needed

$keyword = $_GET['symptom'] ?? '';
$data = [];

if ($keyword !== '') {
    // Escape LIKE special characters and add wildcards
    $like = str_replace(['%', '_'], ['\%', '\_'], $keyword);
    $like = "%" . $like . "%";

    $stmt = $conn->prepare("
        SELECT *
        FROM suggestions
        WHERE symptom_keywords LIKE ?
        ORDER BY category
    ");
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $cat = $row['category'] ?? 'Other';
        $data[$cat][] = $row;
    }
    $stmt->close();
} else {
    die("❗ Symptom keyword is missing.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Suggestions</title>
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #e3fdfd; font-family: 'Poppins', sans-serif; }
    .container { padding-top: 40px; }
    h2 { text-align: center; color: #027373; margin-bottom: 30px; }
    .section-title { font-size: 1.4rem; margin-top: 30px; color: #026d6d; }
    .card { margin-bottom: 20px; }
  </style>
</head>
<body>
<div class="container">
  <h2>Suggestions for: <span class="text-primary"><?= htmlspecialchars($keyword) ?></span></h2>

  <?php if (empty($data)): ?>
    <div class="alert alert-warning text-center mt-4">
      ❌ No suggestions found for this keyword.
    </div>
  <?php else: ?>
    <?php foreach ($data as $category => $items): ?>
      <h3 class="section-title"><?= htmlspecialchars($category) ?></h3>
      <div class="row">
        <?php foreach ($items as $row): ?>
          <div class="col-md-4">
            <div class="card p-3 shadow-sm">
              <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($row['description'] ?? '') ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
