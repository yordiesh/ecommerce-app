<?php
require_once '../config/db.php';

// Insert dummy category if none exist
$catCheck = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
if ($catCheck == 0) {
    $pdo->exec("INSERT INTO categories (name) VALUES ('Electronics'), ('Apparel'), ('Accessories')");
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);
    $category_id = trim($_POST['category_id']);

    $imageName = 'default.jpg';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = '../assets/images/uploads/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                $imageName = $newFileName;
            }
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO products (category_id, name, description, price, stock, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$category_id, $name, $description, $price, $stock, $imageName]);
        $message = "Product added successfully!";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Add Product</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body style="background-color: #f3f4f6;">
    <div class="container" style="max-width: 600px; margin-top: 2rem;">
        <h2>Add New Product</h2>
        <?php if($message): ?>
            <p style="background: #e0f2fe; padding: 1rem; border-radius: 8px; margin: 1rem 0; color: #0369a1;"><?= $message ?></p>
        <?php endif; ?>
        <form action="" method="POST" enctype="multipart/form-data" style="background: white; padding: 2rem; border-radius: 8px; box-shadow: var(--shadow);">
            <div style="margin-bottom: 1rem;">
                <label>Product Name</label><br>
                <input type="text" name="name" required style="width: 100%; padding: 0.5rem; margin-top: 0.25rem;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label>Category</label><br>
                <select name="category_id" required style="width: 100%; padding: 0.5rem; margin-top: 0.25rem;">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label>Price ($)</label><br>
                <input type="number" step="0.01" name="price" required style="width: 100%; padding: 0.5rem; margin-top: 0.25rem;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label>Stock Quantity</label><br>
                <input type="number" name="stock" value="10" required style="width: 100%; padding: 0.5rem; margin-top: 0.25rem;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label>Description</label><br>
                <textarea name="description" rows="4" style="width: 100%; padding: 0.5rem; margin-top: 0.25rem;"></textarea>
            </div>
            <div style="margin-bottom: 1rem;">
                <label>Product Image</label><br>
                <input type="file" name="image" style="margin-top: 0.25rem;">
            </div>
            <button type="submit" class="btn" style="background: var(--primary-color); color: white; width: 100%; border: none; cursor: pointer;">Save Product</button>
        </form>
        <br>
        <a href="index.php">&larr; Back to Admin Dashboard</a>
    </div>
</body>
</html>