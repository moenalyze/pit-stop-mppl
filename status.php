<?php
// status.php
require 'includes/db.php';
require 'includes/auth.php';

$plate = $_GET['plate'] ?? '';
$rows = [];
if ($plate) {
    // Logic sama
    $stmt = $pdo->prepare("
      SELECT b.*, s.name AS service_name, u.name AS customer_name
      FROM bookings b
      JOIN services s ON s.id = b.service_id
      JOIN users u ON u.id = b.user_id
      JOIN vehicles v ON v.id = b.vehicle_id
      WHERE v.plate = ? ORDER BY b.created_at DESC
    ");
    $stmt->execute([$plate]);
    $rows = $stmt->fetchAll();
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Check Status - PitStop</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
      <div class="nav-wrapper">
          <a href="index.php" class="logo">PitStop.id</a>
          <nav><a href="index.php">Home</a></nav>
      </div>
    </header>

    <div class="container" style="padding-top: 3rem;">
        <h2 style="margin-bottom: 1.5rem;">Track Service Status</h2>
        
        <div class="glass-panel" style="padding: 1.5rem; margin-bottom: 2rem;">
            <form method="get" style="display: flex; gap: 1rem;">
                <input name="plate" value="<?= htmlspecialchars($plate) ?>" placeholder="Enter Vehicle Plate Number" style="flex: 1;">
                <button class="btn btn-outline" type="submit">Track</button>
            </form>
        </div>

        <?php if($rows): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Service Info</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th>Est. Price</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($rows as $r): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 500; color: var(--primary);"><?= htmlspecialchars($r['service_name']) ?></div>
                                <div style="font-size: 0.8rem; color: var(--secondary); margin-top: 4px;">ID: #<?= $r['id'] ?></div>
                            </td>
                            <td>
                                <?= date('M d, Y', strtotime($r['booking_date'])) ?>
                                <div style="font-size: 0.8rem; color: var(--secondary);"><?= $r['time_slot'] ?></div>
                            </td>
                            <td>
                                <?php 
                                    $s = $r['status'];
                                    $cls = 'status-waiting';
                                    if($s == 'completed') $cls = 'status-done';
                                    if($s == 'process') $cls = 'status-process';
                                ?>
                                <span class="status-badge <?= $cls ?>"><?= $s ?></span>
                            </td>
                            <td style="font-family: monospace;">Rp <?= number_format($r['estimate_price'],0,',','.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif($plate): ?>
            <p style="text-align: center; margin-top: 2rem;">No service history found for this vehicle.</p>
        <?php endif; ?>
    </div>
</body>
</html>