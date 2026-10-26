<?php
require_once '../config/db.php';
$stmt = $pdo->query("SELECT o.*, u.name as customer_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC");
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body style="background: #f3f4f6;">
    <div class="container">
        <h2>Customer Orders</h2>
        <br>
        <a href="index.php" class="btn" style="background: #e5e7eb; color: #374151;">&larr; Dashboard</a>
        <br><br>
        <table style="width: 100%; background: white; border-collapse: collapse; box-shadow: var(--shadow); border-radius: 8px; overflow: hidden;">
            <thead>
                <tr style="background: #f3f4f6; text-align: left;">
                    <th style="padding: 1rem;">Order ID</th>
                    <th style="padding: 1rem;">Customer</th>
                    <th style="padding: 1rem;">Total Amount</th>
                    <th style="padding: 1rem;">Status</th>
                    <th style="padding: 1rem;">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $o): ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 1rem;">#<?= $o['id'] ?></td>
                    <td style="padding: 1rem;"><?= htmlspecialchars($o['customer_name']) ?></td>
                    <td style="padding: 1rem;">$<?= number_format($o['total_amount'], 2) ?></td>
                    <td style="padding: 1rem;"><span style="background: #e0f2fe; padding: 0.25rem 0.5rem; border-radius: 4px; color: #0369a1;"><?= $o['status'] ?></span></td>
                    <td style="padding: 1rem;"><?= $o['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>