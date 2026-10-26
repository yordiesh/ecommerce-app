<?php
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/header.php';

// Handle item removal
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($action === 'remove' && isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
    header('Location: cart.php');
    exit;
}

// Handle quantity updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    if (isset($_POST['quantities']) && is_array($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $id => $qty) {
            $id = (int)$id;
            $qty = (int)$qty;
            if ($qty > 0) {
                $_SESSION['cart'][$id] = $qty;
            } else {
                unset($_SESSION['cart'][$id]);
            }
        }
    }
    header('Location: cart.php');
    exit;
}

$cartItems = [];
$totalPrice = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    try {
        $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
        $cartItems = $stmt->fetchAll();
    } catch (PDOException $e) {
        $cartItems = [];
    }
}
?>

<h2>Shopping Cart</h2>
<br>

<?php if (!empty($cartItems)): ?>
    <form method="POST">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border-color); text-align: left;">
                        <th style="padding: 1rem;">Product</th>
                        <th style="padding: 1rem;">Price</th>
                        <th style="padding: 1rem;">Quantity</th>
                        <th style="padding: 1rem;">Total</th>
                        <th style="padding: 1rem;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): 
                        $quantity = $_SESSION['cart'][$item['id']] ?? 1;
                        $subtotal = $item['price'] * $quantity;
                        $totalPrice += $subtotal;
                    ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 1rem; display: flex; align-items: center; gap: 1rem;">
                                <?php if (!empty($item['image_url']) && $item['image_url'] !== 'default.jpg'): ?>
                                    <img src="assets/images/uploads/<?= htmlspecialchars($item['image_url']) ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                <?php endif; ?>
                                <span><?= htmlspecialchars($item['name']) ?></span>
                            </td>
                            <td style="padding: 1rem;">$<?= number_format($item['price'], 2) ?></td>
                            <td style="padding: 1rem;">
                                <input type="number" name="quantities[<?= $item['id'] ?>]" value="<?= $quantity ?>" min="1" max="<?= $item['stock'] ?? 99 ?>" style="width: 60px; padding: 0.4rem; border: 1px solid var(--border-color); border-radius: 4px;">
                            </td>
                            <td style="padding: 1rem;">$<?= number_format($subtotal, 2) ?></td>
                            <td style="padding: 1rem;">
                                <a href="cart.php?action=remove&id=<?= $item['id'] ?>" style="color: #e74c3c; text-decoration: none;">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; flex-wrap: wrap; gap: 1rem;">
            <button type="submit" name="update_cart" class="btn" style="background-color: #6b7280; color: white; padding: 0.75rem 1.5rem; border-radius: 4px; border: none; cursor: pointer;">Update Cart</button>
            <div style="display: flex; align-items: center; gap: 2rem;">
                <h3>Total: $<?= number_format($totalPrice, 2) ?></h3>
                <a href="checkout.php" class="btn" style="background-color: var(--primary-color); color: white; padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none;">Proceed to Checkout</a>
            </div>
        </div>
    </form>
<?php else: ?>
    <p>Your cart is empty. <a href="shop.php" style="color: var(--primary-color);">Browse shop</a></p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>