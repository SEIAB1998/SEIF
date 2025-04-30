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
    <title>Connexion - Marketplace</title>
    <link rel="stylesheet" href="css/main.css">
    <!-- Page specific CSS -->
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main>
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <h2>Connexion</h2>
                    <div class="decoration-line"></div>
                </div>

                <form id="login-form" action="process_login.php" method="POST">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur ou Email</label>
                        <input type="text" id="username" name="username" required placeholder="Entrez votre identifiant">
                        <div class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" required placeholder="Entrez votre mot de passe">
                            <button type="button" class="toggle-password" aria-label="Afficher le mot de passe">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <span>Se connecter</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="auth-footer">
                        <p>Pas encore de compte? <a href="register.php">Créer un compte</a></p>
                        <a href="forgot-password.php" class="forgot-password">Mot de passe oublié?</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="script.js"></script>
</body>
</html>