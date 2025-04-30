<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Marketplace</title>
    <link rel="stylesheet" href="css/main.css">
    <!-- Page specific CSS -->
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="auth-form">
        <h2>Créer un compte</h2>
        <form id="register-form" action="process_register.php" method="POST">
            <div class="form-group">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirmer le mot de passe:</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>
            <button type="submit">S'inscrire</button>
        </form>
        <p>Déjà un compte? <a href="login.php">Connectez-vous</a></p>
    </main>

    <script src="script.js"></script>
</body>
</html>