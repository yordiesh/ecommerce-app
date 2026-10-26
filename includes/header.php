<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexusShop</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header style="border-bottom: 1px solid var(--border-color); padding: 1rem 2rem; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
            <a href="index.php" style="text-decoration: none; font-size: 1.5rem; font-weight: bold; display: flex; align-items: center; gap: 0.5rem; color: #4f46e5;">
            </a>
            <nav style="display: flex; gap: 1.5rem; align-items: center;">
                <a href="index.php" style="text-decoration: none; color: #333; font-weight: 500;">Home</a>
                <a href="shop.php" style="text-decoration: none; color: #333; font-weight: 500;">Shop</a>
                <a href="cart.php" style="text-decoration: none; color: #333; font-weight: 500;">Cart 🛒 (<?= $cartCount ?>)</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span style="color: #666; font-size: 0.9rem;">Hi, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                    <a href="logout.php" style="text-decoration: none; color: #e74c3c; font-weight: 500;">Logout</a>
                <?php else: ?>
                    <a href="login.php" style="text-decoration: none; color: #333; font-weight: 500;">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main style="max-width: 1200px; margin: 2rem auto; padding: 0 1rem; min-height: 70vh;">