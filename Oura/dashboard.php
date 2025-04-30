<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Marketplace</title>
    <link rel="stylesheet" href="css/main.css">
    <!-- Page specific CSS -->
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="dashboard-container">
        <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

        <section class="dashboard-section">
            <h3>Vos produits</h3>
            <div class="user-products" id="user-products">
                <!-- User's products will be loaded here -->
            </div>
            <a href="add_product.php" class="btn">Ajouter un produit</a>
        </section>

        <section class="dashboard-section">
            <h3>Vos achats</h3>
            <div class="user-purchases" id="user-purchases">
                <!-- User's purchases will be loaded here -->
            </div>
        </section>
    </main>

    <script src="script.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load user's products
        fetch('get_user_products.php')
            .then(response => response.json())
            .then(products => {
                const container = document.getElementById('user-products');
                renderProducts(products, container);
            });

        // Load user's purchases
        fetch('get_user_purchases.php')
            .then(response => response.json())
            .then(purchases => {
                const container = document.getElementById('user-purchases');
                renderPurchases(purchases, container);
            });
    });

    function renderProducts(products, container) {
        container.innerHTML = '';

        if (products.length === 0) {
            container.innerHTML = '<p>Vous n\'avez pas encore ajouté de produits.</p>';
            return;
        }

        products.forEach(product => {
            const productCard = document.createElement('div');
            productCard.className = 'product-card';
            productCard.innerHTML = `
                <div class="product-image">
                    ${product.image_path ? `<img src="${product.image_path}" alt="${product.name}">` : 'Image non disponible'}
                </div>
                <div class="product-info">
                    <h3>${product.name}</h3>
                    <p>${product.description.substring(0, 50)}...</p>
                    <p class="price">${product.price} €</p>
                    <button class="btn-small" onclick="editProduct(${product.id})">Modifier</button>
                    <button class="btn-small btn-danger" onclick="deleteProduct(${product.id})">Supprimer</button>
                </div>
            `;
            container.appendChild(productCard);
        });
    }

    function renderPurchases(purchases, container) {
        container.innerHTML = '';

        if (purchases.length === 0) {
            container.innerHTML = '<p>Vous n\'avez pas encore effectué d\'achats.</p>';
            return;
        }

        purchases.forEach(purchase => {
            const purchaseCard = document.createElement('div');
            purchaseCard.className = 'purchase-card';
            purchaseCard.innerHTML = `
                <div class="purchase-info">
                    <h3>${purchase.product_name}</h3>
                    <p>Acheté le: ${new Date(purchase.purchase_date).toLocaleDateString()}</p>
                    <p>Prix: ${purchase.price} €</p>
                    <p>Vendeur: ${purchase.seller_name}</p>
                </div>
            `;
            container.appendChild(purchaseCard);
        });
    }
    </script>
</body>
</html>