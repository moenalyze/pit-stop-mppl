<?php
require '../includes/db.php';
require '../includes/auth.php';
require_login();

$user_id = $_SESSION['user']['id'];
$bookings = $pdo->prepare("
  SELECT b.id, s.name AS service, b.booking_date, b.status, b.estimate_price
  FROM bookings b
  JOIN services s ON s.id=b.service_id
  WHERE b.user_id = ?
  ORDER BY b.id DESC
");
$bookings->execute([$user_id]);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Dashboard - PitStop</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
      <div class="nav-wrapper">
          <a href="../index.php" class="logo">PitStop.id</a>
          <nav>
            <a href="../index.php">Home</a>
            <a href="../logout.php" style="color: #ef4444;">Logout</a>
          </nav>
      </div>
    </header>

    <div class="container" style="padding-top: 3rem;">
        <div style="margin-bottom: 2rem;">
            <h2>My Bookings</h2>
            <p>Track your service history and current status.</p>
        </div>

        <?php if($bookings->rowCount() > 0): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Service Detail</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Est. Price</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($bookings as $b): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--primary);"><?= htmlspecialchars($b['service']) ?></strong>
                                <div style="font-size: 0.8rem; color: var(--secondary);">Order ID: #<?= $b['id'] ?></div>
                            </td>
                            <td><?= date('d M Y', strtotime($b['booking_date'])) ?></td>
                            <td>
                                <?php 
                                    $s = $b['status'];
                                    $cls = 'status-waiting';
                                    if($s == 'completed' || $s == 'done') $cls = 'status-done';
                                    if($s == 'process' || $s == 'in_progress') $cls = 'status-process';
                                ?>
                                <span class="status-badge <?= $cls ?>"><?= ucfirst($s) ?></span>
                            </td>
                            <td style="font-family: monospace;">Rp <?= number_format($b['estimate_price'],0,',','.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="glass-panel" style="text-align: center;">
                <p>You haven't booked any service yet.</p>
                <a href="../booking.php" class="btn btn-gradient" style="margin-top: 1rem;">Book Now</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>