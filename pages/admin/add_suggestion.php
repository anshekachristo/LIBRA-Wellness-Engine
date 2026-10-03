<?php
include(__DIR__ . "/../../db/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $desc = $_POST['description'];
    $keywords = $_POST['keywords'];

    // Upload image
    $imagePath = '';
    if (!empty($_FILES['image']['name'])) {
        $imageName = basename(htmlspecialchars($_FILES['image']['name']));
        $imagePath = "assets/img/" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], "../../" . $imagePath);
    }

    // Upload audio (only for Music)
    $audioPath = '';
    if ($type === 'Music' && !empty($_FILES['audio']['name'])) {
        $audioName = basename(htmlspecialchars($_FILES['audio']['name']));
        $audioPath = "assets/music/" . $audioName;
        move_uploaded_file($_FILES['audio']['tmp_name'], "../../" . $audioPath);
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO suggestions (title, type, image, audio, description, symptom_keywords)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $type, $imagePath, $audioPath, $desc, $keywords);

    if ($stmt->execute()) {
        header("Location: dashboard.php?added=1");
        exit;
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Suggestion - Admin</title>
  <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #e8f8f5;
      font-family: 'Poppins', sans-serif;
    }
    .container {
      max-width: 600px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    h3 {
      text-align: center;
      margin-bottom: 30px;
      color: #2e937a;
    }
  </style>
</head>
<body>
  <div class="container">
    <h3>➕ Add New Suggestion</h3>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Type</label>
        <select name="type" class="form-select" required>
          <option value="">-- Select --</option>
          <option value="Medicine">Medicine</option>
          <option value="Remedy">Remedy</option>
          <option value="Exercise">Exercise</option>
          <option value="Music">Music</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Image (optional)</label>
        <input type="file" name="image" class="form-control" accept="image/*">
      </div>

      <div class="mb-3">
        <label class="form-label">Audio (for Music type only)</label>
        <input type="file" name="audio" class="form-control" accept="audio/*">
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3" required></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Symptom Keywords (comma-separated)</label>
        <input type="text" name="keywords" class="form-control" placeholder="e.g., headache, fever, stress" required>
      </div>

      <div class="text-center">
        <button type="submit" class="btn btn-success">✅ Add Suggestion</button>
        <a href="dashboard.php" class="btn btn-secondary">↩️ Cancel</a>
      </div>
    </form>
  </div>
</body>
</html>
