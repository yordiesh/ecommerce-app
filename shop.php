<?php
require_once 'config/db.php';
include 'includes/header.php';

try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    $products = [];
}
?>

<h2>All Products</h2>
<br>

<div class="product-grid">
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-img">
                    <?php if (!empty($product['image_url']) && $product['image_url'] !== 'default.jpg'): ?>
                        <img src="assets/images/uploads/<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width:100%; height:180px; object-fit:cover;">
                    <?php else: ?>
                        <span>No Image</span>
                    <?php endif; ?>
                </div>
                <div class="product-info">
                    <h3 class="product-title"><?= htmlspecialchars($product['name']) ?></h3>
                    <div class="product-price">$<?= number_format($product['price'], 2) ?></div>
                    <a href="product-details.php?id=<?= $product['id'] ?>" class="btn" style="background-color: var(--primary-color); color: white; width: 100%; text-align: center;">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products found. Go to the admin panel to add some items!</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>