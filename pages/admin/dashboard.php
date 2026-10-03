<?php
session_start();
include(__DIR__ . "/../../db/db.php"); // ✅ Fixed path to db.php

// ✅ Only allow admin
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@libra.com') {
    header("Location: ../../login.php");
    exit;
}

// ✅ Fetch all suggestions
$result = $conn->query("SELECT * FROM suggestions ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Libra</title>
  <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f0fbfa;
      font-family: 'Poppins', sans-serif;
    }
    .container {
      margin-top: 50px;
    }
    .table th {
      background-color: #a8edea;
    }
    .btn {
      margin-right: 5px;
    }
    .alert {
      font-size: 14px;
      padding: 10px;
    }
    .table img {
      object-fit: cover;
      border-radius: 8px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2 class="text-center mb-4">🔧 Admin Dashboard</h2>

    <?php if (isset($_GET['added'])): ?>
      <div class="alert alert-success text-center">✅ Suggestion added successfully.</div>
    <?php elseif (isset($_GET['updated'])): ?>
      <div class="alert alert-info text-center">✍️ Suggestion updated successfully.</div>
    <?php elseif (isset($_GET['deleted'])): ?>
      <div class="alert alert-danger text-center">🗑 Suggestion deleted successfully.</div>
    <?php endif; ?>

    <div class="text-end mb-3">
      <a href="add_suggestion.php" class="btn btn-success">➕ Add Suggestion</a>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Type</th>
            <th>Description</th>
            <th>Image</th>
            <th>Audio</th>
            <th>Keywords</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id']; ?></td>
              <td><?= htmlspecialchars($row['title']); ?></td>
              <td><span class="badge bg-info text-dark"><?= $row['type']; ?></span></td>
              <td><?= htmlspecialchars($row['description']); ?></td>
              <td>
                <?php if (!empty($row['image'])): ?>
                  <img src="../../<?= $row['image']; ?>" width="80" height="60">
                <?php else: ?>
                  <small class="text-muted">N/A</small>
                <?php endif; ?>
              </td>
              <td>
                <?php if (!empty($row['audio'])): ?>
                  <audio controls style="width: 100px;">
                    <source src="../../<?= $row['audio']; ?>" type="audio/mpeg">
                  </audio>
                <?php else: ?>
                  <small class="text-muted">N/A</small>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($row['symptom_keywords']); ?></td>
              <td>
                <a href="edit_suggestion.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">✍️ Edit</a>
                <a href="delete_suggestion.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this suggestion?')">🗑 Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>