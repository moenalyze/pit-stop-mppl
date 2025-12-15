<?php
// register.php
require 'includes/db.php';
require 'includes/auth.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Logic sama persis
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$name || !$email || !$password) $errors[] = 'All fields required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';

    if (!$errors) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email already used';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name,email,password,phone) VALUES (?,?,?,?)");
            $stmt->execute([$name,$email,$hash,$phone]);
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register - PitStop</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="glass-panel centered-panel">
            <h2 style="text-align: center; margin-bottom: 2rem;">Create Account</h2>
            
            <?php if($errors): ?>
                <?php foreach($errors as $e): ?>
                    <p style="color: #f87171; font-size: 0.9rem; text-align: center; margin-bottom: 1rem;"><?= $e ?></p>
                <?php endforeach; ?>
            <?php endif; ?>

            <form method="post">
                <div class="form-group"><label>Full Name</label><input name="name" placeholder="John Doe" required></div>
                <div class="form-group"><label>Email</label><input name="email" type="email" placeholder="john@example.com" required></div>
                <div class="form-group"><label>Phone</label><input name="phone" placeholder="0812..."></div>
                <div class="form-group"><label>Password</label><input name="password" type="password" required></div>
                <button class="btn btn-gradient" type="submit" style="width: 100%;">Create Account</button>
            </form>
            <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
                Already have an account? <a href="login.php" style="color: var(--primary);">Login</a>
            </p>
        </div>
    </div>
</body>
</html>