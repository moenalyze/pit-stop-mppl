<?php
require '../includes/db.php';
require '../includes/auth.php';
require_role('mechanic');

if(isset($_POST['id'], $_POST['status'])){
  $stmt = $pdo->prepare("UPDATE bookings SET status=? WHERE id=?");
  $stmt->execute([$_POST['status'], $_POST['id']]);
}

$bookings = $pdo->query("
  SELECT b.id, u.name AS customer, s.name AS service, b.status, b.booking_date
  FROM bookings b
  JOIN users u ON b.user_id=u.id
  JOIN services s ON s.id=b.service_id
  ORDER BY b.id DESC
")->fetchAll();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mechanic Workspace - PitStop</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
      <div class="nav-wrapper">
          <a href="../index.php" class="logo">PitStop.id</a>
          <nav>
              <span style="color: var(--accent);">Mechanic Mode</span>
              <a href="../logout.php">Logout</a>
          </nav>
      </div>
    </header>

    <div class="container" style="padding-top: 3rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h2>Work Queue</h2>
                <p>Manage vehicle service status.</p>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Job ID</th>
                        <th>Customer & Service</th>
                        <th>Schedule</th>
                        <th>Current Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($bookings as $b): ?>
                <tr>
                    <td>#<?= $b['id'] ?></td>
                    <td>
                        <div style="color: var(--primary); font-weight: 500;"><?= htmlspecialchars($b['service']) ?></div>
                        <div style="font-size: 0.85rem; color: var(--secondary);"><?= htmlspecialchars($b['customer']) ?></div>
                    </td>
                    <td><?= date('d/m/Y', strtotime($b['booking_date'])) ?></td>
                    <td>
                        <?php 
                            $cls = 'status-waiting';
                            if($b['status'] == 'done') $cls = 'status-done';
                            if($b['status'] == 'in_progress') $cls = 'status-process';
                        ?>
                        <span class="status-badge <?= $cls ?>"><?= ucfirst(str_replace('_', ' ', $b['status'])) ?></span>
                    </td>
                    <td>
                        <form method="post" style="display: flex; gap: 0.5rem; align-items: center;">
                            <input type="hidden" name="id" value="<?= $b['id'] ?>">
                            <select name="status" style="padding: 0.4rem; font-size: 0.85rem; width: auto; min-width: 120px;">
                                <option value="waiting" <?= $b['status']=='waiting'?'selected':'' ?>>Waiting</option>
                                <option value="in_progress" <?= $b['status']=='in_progress'?'selected':'' ?>>In Progress</option>
                                <option value="done" <?= $b['status']=='done'?'selected':'' ?>>Done</option>
                            </select>
                            <button class="btn btn-outline" type="submit" style="padding: 0.4rem 1rem; font-size: 0.8rem;">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>