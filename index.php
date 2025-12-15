<?php
require 'includes/auth.php';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PitStop.id - Future Auto Care</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
      <div class="nav-wrapper">
          <a href="index.php" class="logo">PitStop.id</a>
          <nav>
            <?php if(is_logged_in()): ?>
              <a href="booking.php">Booking</a>
              <a href="status.php">Status</a>
              <a href="logout.php" style="color: #ef4444;">Logout</a>
            <?php else: ?>
              <a href="login.php">Login</a>
              <a href="register.php" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.8rem;">Register</a>
            <?php endif; ?>
          </nav>
      </div>
    </header>

    <main class="container" style="text-align: center; padding-top: 6rem;">
      <h1 style="font-size: 3.5rem; line-height: 1.1; margin-bottom: 1.5rem; background: linear-gradient(to right, #fff, #a1a1aa); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
        Modern Auto Care<br>Powered by Digital.
      </h1>
      <p style="font-size: 1.25rem; max-width: 600px; margin: 0 auto 3rem auto;">
        Experience seamless vehicle service management. Real-time tracking, transparent pricing, and instant booking without the queue.
      </p>

      <div style="display: flex; gap: 1rem; justify-content: center; margin-bottom: 4rem;">
        <a class="btn btn-gradient" href="booking.php">Book Service</a>
        <a class="btn btn-outline" href="status.php">Track Status</a>
      </div>

      <?php if(is_logged_in()): ?>
        <div class="glass-panel" style="text-align: left; display: flex; align-items: center; justify-content: space-between; max-width: 700px; margin: 0 auto;">
          <div>
            <h3 style="margin-bottom: 0.5rem;">Welcome back, <?= htmlspecialchars($_SESSION['user']['name']); ?></h3>
            <p style="font-size: 0.9rem;">You are logged in as <span style="color: var(--accent);"><?= ucfirst($_SESSION['user']['role']); ?></span></p>
          </div>
          <a href="dashboard/<?= $_SESSION['user']['role'] ?>.php" class="btn btn-outline">Go to Dashboard &rarr;</a>
        </div>
      <?php endif; ?>
    </main>

    <footer>
      © 2025 PitStop.id — Intelligent Workshop System
    </footer>
</body>
</html>