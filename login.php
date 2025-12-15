<?php
require 'includes/db.php';
require 'includes/auth.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Logic sama persis, tidak diubah
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role'],
            'email' => $user['email']
        ];
        switch ($user['role']) {
            case 'manager': header('Location: dashboard/manager.php'); break;
            case 'cashier': header('Location: dashboard/cashier.php'); break;
            case 'mechanic': header('Location: dashboard/mechanic.php'); break;
            default: header('Location: dashboard/customer.php'); break;
        }
        exit;
    } else {
        $err = 'Invalid credentials.';
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login - PitStop</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="glass-panel centered-panel">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h2 style="margin-bottom: 0.5rem;">Sign In</h2>
                <p>Welcome back to PitStop</p>
            </div>
            
            <?php if($err): ?>
                <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #f87171; padding: 0.75rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                    <?= $err ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label>Email Address</label>
                    <input name="email" type="email" placeholder="name@example.com" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input name="password" type="password" placeholder="••••••••" required>
                </div>
                <button class="btn btn-gradient" type="submit" style="width: 100%;">Continue</button>
            </form>
            
            <p style="text-align: center; margin-top: 2rem; font-size: 0.9rem;">
                Don't have an account? <a href="register.php" style="color: var(--primary);">Sign up</a>
            </p>
        </div>
        <div style="text-align: center;">
             <a href="index.php" style="color: var(--secondary); font-size: 0.9rem;">&larr; Back to Home</a>
        </div>
    </div>
</body>
</html>