<?php
session_start();
include(__DIR__ . '/../db/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Patient Info - Libra</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/aos.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #dff6fd, #e7f9f9);
      font-family: 'Poppins', sans-serif;
    }
    .form-box {
      max-width: 800px;
      margin: 60px auto;
      background: #fff;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    .form-box h2 {
      font-weight: bold;
      color: #027373;
      margin-bottom: 30px;
    }
    .form-label {
      font-weight: 500;
      color: #333;
    }
    .form-control:focus, .form-select:focus {
      box-shadow: none;
      border-color: #027373;
    }
    .btn-submit {
      background: #027373;
      color: white;
      padding: 10px 30px;
      border-radius: 50px;
      font-weight: bold;
      border: none;
    }
    .btn-submit:hover {
      background: #014c4c;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="form-box" data-aos="fade-up">
    <h2 class="text-center">📝 Patient Health Information</h2>
    <form action="save_patient_info.php" method="POST" enctype="multipart/form-data">
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" value="<?= $_SESSION['email'] ?? '' ?>" class="form-control" readonly>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label">Age</label>
          <input type="number" name="age" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-select" required>
            <option value="">Select</option>
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Blood Group</label>
          <input type="text" name="blood_group" class="form-control" placeholder="B+, O-, AB..." required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Height (cm)</label>
          <input type="number" step="0.1" name="height" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Weight (kg)</label>
          <input type="number" step="0.1" name="weight" class="form-control" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Symptom</label>
        <input type="text" name="symptom" class="form-control" placeholder="e.g. Headache, Fever..." required>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Address</label>
          <input type="text" name="address" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Medications (if any)</label>
          <input type="text" name="medications" class="form-control">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label">Body Temperature (°C)</label>
          <input type="text" name="temperature" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Blood Pressure</label>
          <input type="text" name="bp" class="form-control" placeholder="e.g. 120/80">
        </div>
        <div class="col-md-4">
          <label class="form-label">Sugar Level (mg/dL)</label>
          <input type="text" name="sugar" class="form-control">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Medical History / Note</label>
        <textarea name="medical_history" class="form-control" rows="3" placeholder="Extra note or info about patient..."></textarea>
      </div>

      <div class="text-center">
        <button type="submit" class="btn btn-submit">Submit</button>
      </div>
    </form>
  </div>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/aos.js"></script>
<script>
  AOS.init();
</script>
</body>
</html>
