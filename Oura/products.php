<?php
session_start();
require_once 'config.php';

// Get products from database
try {
    $search = isset($_GET['search']) ? "%{$_GET['search']}%" : '%';
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY created_at DESC");
    $stmt->execute([$search, $search]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits - Marketplace</title>
    <link rel="stylesheet" href="css/main.css">
    <!-- Page specific CSS -->
    <link rel="stylesheet" href="css/products.css">
    <!-- You can include FontAwesome if you use their icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="products-container">
        <!-- Search Section -->
        <section class="search-section">
            <h2>Rechercher des produits</h2>
            <form id="search-form" method="GET" action="products.php">
                <input type="text" id="search-query" name="search" placeholder="Nom du produit, catégorie..."
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit">Rechercher</button>
            </form>
        </section>

        <!-- Products Listing -->
        <section class="products-listing">
            <h2>Tous les produits</h2>
            <div id="products-container" class="products-grid">
                <?php if (isset($error)): ?>
                    <p>Erreur: <?php echo htmlspecialchars($error); ?></p>
                <?php elseif (empty($products)): ?>
                    <p>Aucun produit trouvé.</p>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php if (!empty($product['image_path'])): ?>
                                    <img src="<?php echo htmlspecialchars($product['image_path']); ?>"
                                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php else: ?>
                                    Image non disponible
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p><?php echo htmlspecialchars(substr($product['description'], 0, 50)); ?>...</p>
                                <p class="price"><?php echo htmlspecialchars($product['price']); ?> €</p>
                                <button class="btn" onclick="addToCart(<?php echo $product['id']; ?>)">Ajouter au panier</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Cart Icon in Bottom Right Corner -->
    <div class="cart-logo">
        <a href="cart.php">
            <i class="fas fa-shopping-cart"></i>
        </a>
    </div>

    <script src="script.js"></script>
</body>
</html>
