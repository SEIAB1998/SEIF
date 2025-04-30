<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$productId = isset($_GET['id']) ? $_GET['id'] : 0;

// Check if the product exists and belongs to the user
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND user_id = ?");
    $stmt->execute([$productId, $userId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        header("Location: dashboard.php?error=product_not_found");
        exit();
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un produit - Marketplace</title>
    <link rel="stylesheet" href="css/main.css">
    <!-- Page specific CSS -->
    <link rel="stylesheet" href="css/components.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="form-container">
        <h2>Modifier le produit</h2>
        <form action="update_product.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
            
            <div class="form-group">
                <label for="name">Nom du produit:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Prix (€):</label>
                <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Catégorie:</label>
                <select id="category" name="category">
                    <option value="electronics" <?php echo $product['category'] === 'electronics' ? 'selected' : ''; ?>>Électronique</option>
                    <option value="clothing" <?php echo $product['category'] === 'clothing' ? 'selected' : ''; ?>>Vêtements</option>
                    <option value="home" <?php echo $product['category'] === 'home' ? 'selected' : ''; ?>>Maison</option>
                    <option value="other" <?php echo $product['category'] === 'other' ? 'selected' : ''; ?>>Autre</option>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Image du produit:</label>
                <?php if ($product['image_path']): ?>
                    <div class="current-image">
                        <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="max-width: 200px;">
                        <p>Image actuelle</p>
                    </div>
                <?php endif; ?>
                <input type="file" id="image" name="image" accept="image/*">
                <small>Laissez vide pour conserver l'image actuelle</small>
            </div>
            <button type="submit">Mettre à jour le produit</button>
        </form>
    </main>

    <script src="script.js"></script>
</body>
</html>
