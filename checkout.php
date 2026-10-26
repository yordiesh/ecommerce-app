<?php
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/header.php';

if (empty($_SESSION['cart'])) {
    echo "<script>window.location.href='cart.php';</script>";
    exit;
}

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (empty($name) || empty($email) || empty($address)) {
        $error = "Please fill in all checkout fields.";
    } else {
        try {
            $pdo->beginTransaction();

            $ids = implode(',', array_keys($_SESSION['cart']));
            $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
            $cartItems = $stmt->fetchAll();

            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $qty = $_SESSION['cart'][$item['id']];
                // Check stock availability
                if ($item['stock'] < $qty) {
                    throw new Exception("Sorry, '{$item['name']}' only has {$item['stock']} items left in stock.");
                }
                $totalAmount += $item['price'] * $qty;
            }

            $userId = $_SESSION['user_id'] ?? null;
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, shipping_address, status) VALUES (?, ?, ?, 'Pending')");
            $stmt->execute([$userId, $totalAmount, $address]);
            $orderId = $pdo->lastInsertId();

            $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stockStmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

            foreach ($cartItems as $item) {
                $qty = $_SESSION['cart'][$item['id']];
                $itemStmt->execute([$orderId, $item['id'], $qty, $item['price']]);
                // Deduct stock
                $stockStmt->execute([$qty, $item['id']]);
            }

            $pdo->commit();
            unset($_SESSION['cart']);
            $success = true;
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Checkout failed: " . $e->getMessage();
        }
    }
}
?>

<h2>Checkout</h2>
<br>

<?php if ($success): ?>
    <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
        Order placed successfully! Thank you for your purchase. Inventory has been updated.
    </div>
    <a href="shop.php" class="btn" style="background-color: var(--primary-color); color: white; padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none;">Continue Shopping</a>
<?php else: ?>
    <?php if (!empty($error)): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" style="max-width: 600px;">
        <div style="margin-bottom: 1rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Full Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 4px;">
        </div>
        <div style="margin-bottom: 1rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email Address:</label>
            <input type="email" name="email" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 4px;">
        </div>
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Shipping Address:</label>
            <textarea name="address" rows="4" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 4px;"></textarea>
        </div>
        <button type="submit" class="btn" style="background-color: var(--primary-color); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 4px; cursor: pointer;">Place Order</button>
    </form>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>