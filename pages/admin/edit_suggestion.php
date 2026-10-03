<?php
include(__DIR__ . "/../../db/db.php");
session_start();

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Invalid suggestion ID.");
}

// Fetch existing data
$sql = "SELECT * FROM suggestions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Suggestion not found.");
}

// Update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $keywords = $_POST['keywords'];
    $imagePath = $data['image'];
    $audioPath = $data['audio'];

    // Handle new image upload
    if ($_FILES['image']['error'] == 0) {
        $imageName = basename($_FILES['image']['name']);
        $imagePath = "assets/images/" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], "../../" . $imagePath);
    }

    // Handle new audio upload
    if ($_FILES['audio']['error'] == 0) {
        $audioName = basename($_FILES['audio']['name']);
        $audioPath = "assets/music/" . $audioName;
        move_uploaded_file($_FILES['audio']['tmp_name'], "../../" . $audioPath);
    }

    $update = "UPDATE suggestions SET title=?, type=?, image=?, audio=?, description=?, symptom_keywords=? WHERE id=?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("ssssssi", $title, $type, $imagePath, $audioPath, $description, $keywords, $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php?updated=1");
        exit;
    } else {
        echo "❌ Update failed: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Suggestion</title>
  <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/css/aos.css" rel="stylesheet">
  <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" data-aos="fade-up">
  <h2 class="text-center mb-4">✏️ Edit Suggestion</h2>

  <form method="POST" enctype="multipart/form-data" action="" class="card p-4 shadow-sm bg-white">
    <div class="mb-3">
      <label class="form-label">Title</label>
      <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($data['title']); ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Type</label>
      <select name="type" class="form-control" required>
        <option value="">-- Select --</option>
        <?php
        $types = ['Medicine', 'Remedy', 'Exercise', 'Music'];
        foreach ($types as $t) {
          $selected = ($data['type'] === $t) ? "selected" : "";
          echo "<option value='$t' $selected>$t</option>";
        }
        ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Current Image</label><br>
      <?php if ($data['image']): ?>
        <img src="<?php echo $data['image']; ?>" width="100"><br>
      <?php endif; ?>
      <input type="file" name="image" class="form-control mt-2">
    </div>

    <div class="mb-3">
      <label class="form-label">Current Audio</label><br>
      <?php if ($data['audio']): ?>
        <audio controls src="<?php echo $data['audio']; ?>" style="width: 100%;"></audio><br>
      <?php endif; ?>
      <input type="file" name="audio" class="form-control mt-2">
    </div>

    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" required><?php echo htmlspecialchars($data['description']); ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Symptom Keywords (comma-separated)</label>
      <input type="text" name="keywords" class="form-control" value="<?php echo htmlspecialchars($data['symptom_keywords']); ?>">
    </div>

    <div class="d-flex justify-content-between">
      <a href="dashboard.php" class="btn btn-secondary">← Cancel</a>
      <button type="submit" class="btn btn-primary">📃 Update Suggestion</button>
    </div>
  </form>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/aos.js"></script>
<script>AOS.init();</script>
</body>
</html>