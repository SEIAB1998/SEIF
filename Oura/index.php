<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link rel="stylesheet" href="css/main.css">
    <!-- Page specific CSS -->
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main>
        <section class="hero">
            <h2>Bienvenue sur notre Marketplace</h2>
            <p>Achetez et vendez des produits facilement</p>
        </section>

        <section class="featured-products">
            
            <div id="products-container" class="products-grid">
                
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Marketplace. Tous droits réservés.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
