<?php
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
} catch (PDOException $e) {
    $product = false;
}

// Handle Add to Cart form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productId = $product['id'];
    $quantity = max(1, (int)$_POST['quantity']);
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
    
    echo "<script>alert('Product added to cart successfully!'); window.location.href='cart.php';</script>";
    exit;
}
?>

<?php if ($product): ?>
    <div style="display: flex; gap: 2rem; margin-top: 2rem; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
            <?php if (!empty($product['image_url']) && $product['image_url'] !== 'default.jpg'): ?>
                <img src="assets/images/uploads/<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width:100%; border-radius: 8px; object-fit: cover; max-height: 400px;">
            <?php else: ?>
                <div style="background: var(--border-color); height: 300px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">No Image Available</div>
            <?php endif; ?>
        </div>
        <div style="flex: 1; min-width: 300px;">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <p style="font-size: 1.5rem; color: var(--primary-color); font-weight: bold; margin: 1rem 0;">$<?= number_format($product['price'], 2) ?></p>
            <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 2rem;"><?= htmlspecialchars($product['description'] ?? 'No description available for this product.') ?></p>
            
            <form method="POST">
                <div style="margin-bottom: 1.5rem;">
                    <label for="quantity" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" style="padding: 0.5rem; width: 80px; border: 1px solid var(--border-color); border-radius: 4px;">
                </div>
                <button type="submit" name="add_to_cart" class="btn" style="background-color: var(--primary-color); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 4px; cursor: pointer; font-size: 1rem;">Add to Cart 🛒</button>
            </form>
        </div>
    </div>
<?php else: ?>
    <h2>Product Not Found</h2>
    <p>The product you are looking for does not exist.</p>
    <br>
    <a href="shop.php" class="btn">Back to Shop</a>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>