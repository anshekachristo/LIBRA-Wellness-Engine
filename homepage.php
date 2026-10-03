<?php
session_start();
include(__DIR__ . "/db/db.php"); // Correct path to db.php

$patientInfo = null;

// Fetch patient info if user is logged in
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT * FROM patient_info WHERE user_id = ? LIMIT 1");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $patientInfo = $result->fetch_assoc();
    }

    $stmt->close();
}

// Function to get random suggestions
function getSuggestions($conn, $type, $limit = 20) {
    $stmt = $conn->prepare("SELECT * FROM suggestions WHERE type = ? ORDER BY RAND() LIMIT ?");
    $stmt->bind_param("si", $type, $limit);
    $stmt->execute();
    return $stmt->get_result();
}

$medicines = getSuggestions($conn, "Medicine");
$remedies = getSuggestions($conn, "Remedy");
$exercises = getSuggestions($conn, "Exercise");
$music = getSuggestions($conn, "Music");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Libra - Homepage</title>
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/aos.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to bottom right, #dff6fd, #e7f9f9);
    }

    .hero {
      background: linear-gradient(to right, #0acffe, #495aff);
      color: white;
      padding: 60px 20px 120px;
      text-align: center;
      position: relative;
    }

    .hero h1 {
      font-weight: bold;
      font-size: 3rem;
    }

    .hero input {
      max-width: 500px;
      margin: 20px auto;
    }

    .btn-get-started {
      background-color: white;
      color: #027373;
      font-weight: bold;
      border-radius: 50px;
      padding: 10px 25px;
      transition: 0.3s;
      text-decoration: none;
    }

    .btn-get-started:hover {
      background-color: #027373;
      color: white;
    }

    .user-profile-box {
      position: absolute;
      top: 20px;
      left: 20px;
      background: rgba(255, 255, 255, 0.2);
      padding: 10px 15px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      gap: 10px;
      color: #fff;
      backdrop-filter: blur(4px);
      text-decoration: none;
    }

    .user-profile-box img {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #fff;
    }

    .category-section {
      padding: 60px 0;
    }

    .section-title {
      font-size: 2rem;
      color: #027373;
      margin-bottom: 30px;
    }

    .scrolling-wrapper {
      display: flex;
      overflow-x: auto;
      gap: 20px;
      padding: 0 10px;
      scroll-snap-type: x mandatory;
    }

    .scrolling-wrapper::-webkit-scrollbar {
      height: 8px;
    }
    .scrolling-wrapper::-webkit-scrollbar-thumb {
      background: #ccc;
      border-radius: 10px;
    }

    .libra-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 15px;
      box-shadow: 0 5px 25px rgba(0,0,0,0.08);
      text-align: center;
      min-width: 220px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      scroll-snap-align: center;
      text-decoration: none;
      color: #000;
    }

    .libra-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    }

    .libra-card img {
      max-height: 120px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 10px;
      width: 100%;
    }
    .user-profile-box {
  position: absolute;
  top: 20px;
  left: 20px;
  background: rgba(0, 0, 0, 0.4);
  padding: 10px 15px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  gap: 10px;
  color: #fff;
  backdrop-filter: blur(4px);
  text-decoration: none;
  z-index: 100;
}

.user-profile-box div {
  font-weight: 600;
  font-size: 1rem;
}

    .floating-music-player {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background: #027373;
      color: white;
      z-index: 1000;
      box-shadow: 0 -4px 10px rgba(0,0,0,0.1);
      font-family: 'Poppins', sans-serif;
    }

    .floating-music-player .d-flex {
      padding: 10px 20px;
    }

    .audio-control {
      width: 240px;
    }
  </style>
</head>
<body>

<!-- Hero Section -->
<div class="hero">
  <?php if ($patientInfo): ?>
    <a href="view_profile.php" class="user-profile-box">
      <img src="uploads/<?= htmlspecialchars($patientInfo['profile_pic']) ?>" alt="Profile Picture">
      <div><strong><?= htmlspecialchars($patientInfo['name']) ?></strong></div>
    </a>
  <?php else: ?>
    <!-- Optional fallback or error box -->
    <div class="user-profile-box" style="background: rgba(255, 0, 0, 0.2);">
      <strong style="color:white;">User not found</strong>
    </div>
  <?php endif; ?>

  <img src="assets/img/libra-logo.png" alt="Libra Logo" height="60">
  <h1>Welcome to Libra</h1>
 <form class="d-flex flex-column align-items-center" onsubmit="return submitSearch();">
  <div style="position: relative; width: 100%; max-width: 500px;">
    <input type="text" id="searchInput" name="symptom" class="form-control mb-3" placeholder="Search your symptom..." required>
    <div id="suggestionsBox" class="list-group position-absolute w-100" style="z-index: 999;"></div>
  </div>
  <button type="submit" class="btn btn-get-started">🎯 Click Here for Suggestions</button>
</form>

</div>


<!-- Categories -->
<div class="container category-section" data-aos="fade-up">
  <h2 class="section-title">💊 Medicines</h2>
  <div class="scrolling-wrapper">
    <?php while ($row = $medicines->fetch_assoc()): ?>
      <a href="view_suggestion.php?id=<?= $row['id'] ?>" class="libra-card">
        <img src="<?= $row['image'] ?>" alt="<?= $row['title'] ?>">
        <h5><?= htmlspecialchars($row['title']) ?></h5>
      </a>
    <?php endwhile; ?>
  </div>
</div>

<div class="container category-section" data-aos="fade-up">
  <h2 class="section-title">🌿 Home Remedies</h2>
  <div class="scrolling-wrapper">
    <?php while ($row = $remedies->fetch_assoc()): ?>
      <a href="view_suggestion.php?id=<?= $row['id'] ?>" class="libra-card">
        <img src="<?= $row['image'] ?>" alt="<?= $row['title'] ?>">
        <h5><?= htmlspecialchars($row['title']) ?></h5>
      </a>
    <?php endwhile; ?>
  </div>
</div>

<div class="container category-section" data-aos="fade-up">
  <h2 class="section-title">🏋️ Exercises</h2>
  <div class="scrolling-wrapper">
    <?php while ($row = $exercises->fetch_assoc()): ?>
      <a href="view_suggestion.php?id=<?= $row['id'] ?>" class="libra-card">
        <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" class="img-fluid">

        <h5><?= htmlspecialchars($row['title']) ?></h5>
      </a>
    <?php endwhile; ?>
  </div>
</div>

<div class="container category-section" data-aos="fade-up">
  <h2 class="section-title">🎵 Relaxing Music</h2>
  <div class="scrolling-wrapper">
    <?php while ($row = $music->fetch_assoc()): ?>
      <a href="view_suggestion.php?id=<?= $row['id'] ?>" class="libra-card">
        <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" class="img-fluid">
        <h5><?= htmlspecialchars($row['title']) ?></h5>
      </a>
    <?php endwhile; ?>
  </div>
</div>




<!-- Scripts -->
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/aos.js"></script>
<script>
  AOS.init();
</script>
<!-- Footer -->
  <footer class="site-footer" style="background: linear-gradient(to right, #4fd1c5, #38b2ac); color: white; padding-top: 60px; padding-bottom: 40px; font-family: 'Poppins', sans-serif;">
    <div class="container">
        <div class="row">

            <!-- Subscribe -->
            <div class="col-lg-6 col-12 mb-5 mb-lg-0">
                <div class="subscribe-form-wrap">
                    <h6 style="color: white; font-weight: 600;">Subscribe. Every weekly.</h6>
                    <form class="custom-form subscribe-form" action="#" method="get" role="form">
                        <input type="email" name="subscribe-email" id="subscribe-email" pattern="[^ @]*@[^ @]*" class="form-control mb-2" placeholder="Email Address" required="">
                        <button type="submit" class="form-control btn btn-dark text-white" id="submit">Subscribe</button>
                    </form>
                </div>
            </div>

            <!-- Contact -->
            <div class="col-lg-3 col-md-6 col-12 mb-4 mb-md-0 mb-lg-0">
                <h6 class="site-footer-title mb-3">Contact</h6>
                <p class="mb-2"><strong class="d-inline me-2">Phone:</strong> 010-020-0340</p>
                <p><strong class="d-inline me-2">Email:</strong> <a href="#" style="color: white; text-decoration: underline;">inquiry@pod.co</a></p>
            </div>

            <!-- Downloads + Social -->
            <div class="col-lg-3 col-md-6 col-12">
                <h6 class="site-footer-title mb-3">Download Mobile</h6>
                <div class="site-footer-thumb mb-4 pb-2">
                    <div class="d-flex flex-wrap">
                        <a href="#"><img src="assets/img/app-store.png" class="me-3 mb-2 img-fluid" alt="App Store" style="max-width: 120px;"></a>
                        <a href="#"><img src="assets/img/play-store.png" class="img-fluid" alt="Play Store" style="max-width: 120px;"></a>
                    </div>
                </div>

                <h6 class="site-footer-title mb-3">Social</h6>
                <ul class="social-icon list-unstyled d-flex gap-3">
                    <li><a href="#" class="text-white fs-4"><i class="bi bi-instagram"></i></a></li>
                    <li><a href="#" class="text-white fs-4"><i class="bi bi-twitter"></i></a></li>
                    <li><a href="#" class="text-white fs-4"><i class="bi bi-whatsapp"></i></a></li>
                </ul>
            </div>

        </div>
    </div>

    <div class="container pt-5">
        <div class="row align-items-center">
            <div class="col-lg-2 col-md-3 col-12">
                <a class="navbar-brand" href="index.html">
                    <img src="assets/img/libra-logo.png" class="logo-image img-fluid" alt="Libra Logo" style="max-height: 60px;">
                </a>
            </div>
            <div class="col-lg-7 col-md-9 col-12">
                <ul class="site-footer-links list-inline mb-0">
                    <li class="list-inline-item me-3"><a href="#" class="text-white">Homepage</a></li>
                    <li class="list-inline-item me-3"><a href="#" class="text-white">Browse</a></li>
                    <li class="list-inline-item me-3"><a href="#" class="text-white">Help Center</a></li>
                    <li class="list-inline-item"><a href="#" class="text-white">Contact Us</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-12">
                <p class="mb-0 text-white-50 small">Copyright © 2036 Libra Health<br>Design by <a href="https://templatemo.com" target="_blank" style="color: white;">TemplateMo</a></p>
            </div>
        </div>
    </div>
</footer>
<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let query = this.value;
    if (query.length > 1) {
        fetch('search_suggestions.php?query=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                let box = document.getElementById('suggestionsBox');
                box.innerHTML = '';
                data.forEach(item => {
                    let div = document.createElement('div');
                    div.className = 'list-group-item list-group-item-action';
                    div.innerHTML = `🔍 ${item.title} <span class="text-muted">(${item.type})</span>`;
                    div.onclick = function () {
                        window.location.href = `view_suggestion.php?id=${item.id}`;
                    };
                    box.appendChild(div);
                });
            });
    } else {
        document.getElementById('suggestionsBox').innerHTML = '';
    }
});
</script>

</body>
</html>
