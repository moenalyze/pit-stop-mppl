<?php
// booking.php
require 'includes/db.php';
require 'includes/auth.php';

$services = $pdo->query("SELECT * FROM services ORDER BY name")->fetchAll();

if (!is_logged_in()) { header("Location: login.php"); exit; }
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Booking - PitStop</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
      <div class="nav-wrapper">
          <a href="index.php" class="logo">PitStop.id</a>
          <nav><a href="logout.php">Logout</a></nav>
      </div>
    </header>

    <div class="container">
        <div class="glass-panel" style="max-width: 600px; margin: 2rem auto;">
            <div style="margin-bottom: 2rem; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">
                <h2>Configure Service</h2>
                <p>Select your preferences for the vehicle service.</p>
            </div>

            <form action="booking_submit.php" method="post">
                <div class="form-group">
                    <label>Vehicle Plate Number</label>
                    <input name="plate" placeholder="e.g., B 1234 CD" style="font-family: monospace; letter-spacing: 2px; text-transform: uppercase;" required>
                </div>

                <div class="form-group">
                    <label>Service Type</label>
                    <select name="service_id" required>
                        <?php foreach($services as $s): ?>
                        <option value="<?= $s['id'] ?>">
                            <?= htmlspecialchars($s['name']) ?> — IDR <?= number_format($s['price'],0,',','.') ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label>Date</label>
                        <input name="booking_date" type="date" required>
                    </div>
                    <div class="form-group">
                        <label>Time</label>
                        <input name="time_slot" type="time">
                    </div>
                </div>

                <div class="form-group">
                    <label>Location Preference</label>
                    <select name="location">
                        <option value="workshop">Workshop Visit</option>
                        <option value="home">Home Service</option>
                    </select>
                </div>

                <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                    <button class="btn btn-gradient" type="submit" style="flex: 2;">Confirm Booking</button>
                    <a href="index.php" class="btn btn-outline" style="flex: 1;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>