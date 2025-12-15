<?php
require '../includes/db.php';
require '../includes/auth.php';
require_role('manager');

// Stats: Total Booking
$totalBookings = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();

// UPDATE LOGIC: Total Revenue dihitung dari status 'paid' (Sesuai update Kasir)
$totalRevenue = $pdo->query("SELECT SUM(amount) FROM transactions WHERE status='paid'")->fetchColumn();

// List Booking Terbaru
$bookings = $pdo->query("SELECT b.id, u.name AS customer, s.name AS service, b.status, b.booking_date 
    FROM bookings b 
    JOIN users u ON b.user_id=u.id
    JOIN services s ON b.service_id=s.id
    ORDER BY b.id DESC LIMIT 10")->fetchAll();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Manager Overview - PitStop</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
      <div class="nav-wrapper">
          <a href="../index.php" class="logo">PitStop.id</a>
          <nav>
              <span style="color: var(--accent);">Manager Mode</span>
              <a href="../logout.php">Logout</a>
          </nav>
      </div>
    </header>

    <div class="container" style="padding-top: 3rem;">
        <h2 style="margin-bottom: 1.5rem;">Business Overview</h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
            <div class="glass-panel">
                <p style="font-size: 0.85rem; text-transform: uppercase; color: var(--secondary); letter-spacing: 0.05em;">Total Bookings</p>
                <h3 style="font-size: 2.5rem; margin-top: 0.5rem; color: var(--primary);"><?= $totalBookings ?></h3>
                <p style="font-size: 0.8rem; margin-top: 0.5rem; color: var(--accent);">Lifetime orders</p>
            </div>
            
            <div class="glass-panel">
                <p style="font-size: 0.85rem; text-transform: uppercase; color: var(--secondary); letter-spacing: 0.05em;">Total Revenue</p>
                <h3 style="font-size: 2.5rem; margin-top: 0.5rem; color: var(--primary);">
                    <span style="font-size: 1.5rem; color: var(--secondary);">Rp</span> <?= number_format($totalRevenue?:0,0,',','.') ?>
                </h3>
                <p style="font-size: 0.8rem; margin-top: 0.5rem; color: #34d399;">
                    Verified 'Paid' Transactions
                </p>
            </div>
        </div>

        <h3 style="margin-bottom: 1rem;">Recent Activity</h3>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($bookings as $b): ?>
                <tr>
                    <td style="color: var(--secondary);">#<?= $b['id'] ?></td>
                    <td style="font-weight: 500; color: var(--primary);"><?= htmlspecialchars($b['customer']) ?></td>
                    <td><?= htmlspecialchars($b['service']) ?></td>
                    <td><?= date('d M Y', strtotime($b['booking_date'])) ?></td>
                    <td>
                        <?php 
                            $cls = 'status-waiting';
                            if($b['status'] == 'completed' || $b['status'] == 'done') $cls = 'status-done';
                            if($b['status'] == 'process' || $b['status'] == 'in_progress') $cls = 'status-process';
                        ?>
                        <span class="status-badge <?= $cls ?>"><?= ucfirst(str_replace('_', ' ', $b['status'])) ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>