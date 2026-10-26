<?php
require_once '../config/db.php';

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: products.php");
    exit;
}

$stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body style="background: #f3f4f6;">
    <div class="container">
        <h2>Manage Products</h2>
        <br>
        <a href="index.php" class="btn" style="background: #e5e7eb; color: #374151;">&larr; Dashboard</a>
        <a href="add-product.php" class="btn" style="background: var(--primary-color); color: white; margin-left: 1rem;">+ Add Product</a>
        <br><br>
        <table style="width: 100%; background: white; border-collapse: collapse; box-shadow: var(--shadow); border-radius: 8px; overflow: hidden;">
            <thead>
                <tr style="background: #f3f4f6; text-align: left;">
                    <th style="padding: 1rem;">ID</th>
                    <th style="padding: 1rem;">Name</th>
                    <th style="padding: 1rem;">Category</th>
                    <th style="padding: 1rem;">Price</th>
                    <th style="padding: 1rem;">Stock</th>
                    <th style="padding: 1rem;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $p): ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 1rem;"><?= $p['id'] ?></td>
                    <td style="padding: 1rem;"><?= htmlspecialchars($p['name']) ?></td>
                    <td style="padding: 1rem;"><?= htmlspecialchars($p['category_name']) ?></td>
                    <td style="padding: 1rem;">$<?= number_format($p['price'], 2) ?></td>
                    <td style="padding: 1rem;"><?= $p['stock'] ?></td>
                    <td style="padding: 1rem;"><a href="products.php?delete=<?= $p['id'] ?>" onclick="return confirm('Delete this product?')" style="color: #dc2626; text-decoration: none; font-weight: 500;">Delete</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>