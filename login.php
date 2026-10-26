<?php
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['is_admin'] = $user['is_admin'] ?? 0;

                if ($_SESSION['is_admin']) {
                    echo "<script>window.location.href='admin/index.php';</script>";
                } else {
                    echo "<script>window.location.href='shop.php';</script>";
                }
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<h2>Login</h2>
<br>

<?php if (!empty($error)): ?>
    <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" style="max-width: 400px;">
    <div style="margin-bottom: 1rem;">
        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email Address:</label>
        <input type="email" name="email" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 4px;">
    </div>
    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Password:</label>
        <input type="password" name="password" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 4px;">
    </div>
    <button type="submit" class="btn" style="background-color: var(--primary-color); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 4px; cursor: pointer; width: 100%;">Login</button>
</form>
<p style="margin-top: 1rem;">Don't have an account? <a href="register.php" style="color: var(--primary-color);">Register here</a></p>

<?php include 'includes/footer.php'; ?>