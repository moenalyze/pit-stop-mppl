<?php
require '../includes/db.php';
require '../includes/auth.php';
require_role('cashier');

// LOGIC: Update Status Transaksi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction_id'], $_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE transactions SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['transaction_id']]);
}

// Ambil data transaksi
$transactions = $pdo->query("
  SELECT t.id, u.name AS customer, s.name AS service, t.amount, t.status 
  FROM transactions t
  JOIN bookings b ON b.id=t.booking_id
  JOIN users u ON b.user_id=u.id
  JOIN services s ON b.service_id=s.id
  ORDER BY t.id DESC
")->fetchAll();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cashier Dashboard - PitStop</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
      <div class="nav-wrapper">
          <a href="../index.php" class="logo">PitStop.id</a>
          <nav>
              <span style="color: var(--accent);">Cashier Mode</span>
              <a href="../logout.php">Logout</a>
          </nav>
      </div>
    </header>

    <div class="container" style="padding-top: 3rem;">
        <div style="margin-bottom: 2rem;">
            <h2>Transaction History</h2>
            <p>Manage payments and update invoice status.</p>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Inv. ID</th>
                        <th>Customer / Service</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th> </tr>
                </thead>
                <tbody>
                <?php foreach($transactions as $t): ?>
                <tr>
                    <td style="font-family: monospace; color: var(--secondary);">#<?= $t['id'] ?></td>
                    <td>
                        <div style="font-weight: 500; color: var(--primary);"><?= htmlspecialchars($t['customer']) ?></div>
                        <div style="font-size: 0.8rem; color: var(--secondary);"><?= htmlspecialchars($t['service']) ?></div>
                    </td>
                    <td style="font-weight: 600;">Rp <?= number_format($t['amount'],0,',','.') ?></td>
                    <td>
                        <?php 
                            $badgeClass = 'status-waiting'; // Default pending
                            if($t['status'] == 'paid') $badgeClass = 'status-done';
                            if($t['status'] == 'failed') $badgeClass = 'status-process'; // Atau buat class merah jika perlu
                        ?>
                        <span class="status-badge <?= $badgeClass ?>">
                            <?= ucfirst($t['status']) ?>
                        </span>
                    </td>
                    <td>
                        <form method="post" style="display: flex; gap: 0.5rem; align-items: center;">
                            <input type="hidden" name="transaction_id" value="<?= $t['id'] ?>">
                            <select name="status" style="padding: 0.4rem; font-size: 0.85rem; width: auto; min-width: 100px;">
                                <option value="pending" <?= $t['status']=='pending'?'selected':'' ?>>Pending</option>
                                <option value="paid" <?= $t['status']=='paid'?'selected':'' ?>>Paid ✅</option>
                                <option value="failed" <?= $t['status']=='failed'?'selected':'' ?>>Failed ❌</option>
                            </select>
                            <button class="btn btn-outline" type="submit" style="padding: 0.4rem 1rem; font-size: 0.8rem;">Save</button>
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