<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit - Marketplace</title>
    <link rel="stylesheet" href="css/main.css">
    <!-- Page specific CSS -->
    <link rel="stylesheet" href="css/components.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="form-container">
        <h2>Ajouter un nouveau produit</h2>
        <form action="process_product.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nom du produit:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Prix (€):</label>
                <input type="number" id="price" name="price" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label for="category">Catégorie:</label>
                <select id="category" name="category">
                    <option value="electronics">Électronique</option>
                    <option value="clothing">Vêtements</option>
                    <option value="home">Maison</option>
                    <option value="other">Autre</option>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Image du produit:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <button type="submit">Ajouter le produit</button>
        </form>
    </main>

    <script src="script.js"></script>
</body>
</html>
