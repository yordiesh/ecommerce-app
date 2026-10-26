<?php
require_once '../config/db.php';

// Fetch summary metrics
$productCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$orderCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body style="background-color: #f3f4f6;">
    <div class="container">
        <h2>⚡ Admin Dashboard</h2>
        <hr style="margin: 1rem 0;">
        <div style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
            <a href="products.php" class="btn" style="background: var(--primary-color); color: white;">Manage Products</a>
            <a href="orders.php" class="btn" style="background: var(--primary-color); color: white;">Manage Orders</a>
            <a href="add-product.php" class="btn" style="background: #10b981; color: white;">+ Add Product</a>
            <a href="../index.php" class="btn" style="background: #e5e7eb; color: #374151;">Visit Store</a>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem;">
            <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: var(--shadow);">
                <h3>Total Products</h3>
                <p style="font-size: 2rem; font-weight: bold; color: var(--primary-color);"><?= $productCount ?></p>
            </div>
            <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: var(--shadow);">
                <h3>Total Orders</h3>
                <p style="font-size: 2rem; font-weight: bold; color: var(--primary-color);"><?= $orderCount ?></p>
            </div>
            <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: var(--shadow);">
                <h3>Registered Users</h3>
                <p style="font-size: 2rem; font-weight: bold; color: var(--primary-color);"><?= $userCount ?></p>
            </div>
        </div>
    </div>
</body>
</html>