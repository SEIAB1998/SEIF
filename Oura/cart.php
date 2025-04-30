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
    <title>Panier - Marketplace</title>
    <link rel="stylesheet" href="css/main.css">
    <!-- Page specific CSS -->
    <link rel="stylesheet" href="css/cart.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="cart-container">
        <h2>Votre panier</h2>
        <div id="cart-items" class="cart-items">
            <!-- Cart items will be loaded here -->
        </div>
        <div class="cart-summary">
            <h3>Résumé</h3>
            <p id="cart-total" class="total">Total: 0 €</p>
            <button id="checkout-btn" class="btn">Passer la commande</button>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        loadCart();

        document.getElementById('checkout-btn').addEventListener('click', checkout);
    });

    function loadCart() {
        fetch('get_cart.php')
            .then(response => response.json())
            .then(cart => {
                const container = document.getElementById('cart-items');
                let total = 0;

                container.innerHTML = '';

                if (cart.length === 0) {
                    container.innerHTML = '<p>Votre panier est vide.</p>';
                    document.getElementById('cart-total').textContent = 'Total: 0 €';
                    return;
                }

                cart.forEach(item => {
                    const cartItem = document.createElement('div');
                    cartItem.className = 'cart-item';
                    cartItem.innerHTML = `
                        <div class="cart-item-image">
                            ${item.image_path ? `<img src="${item.image_path}" alt="${item.name}">` : 'Image non disponible'}
                        </div>
                        <div class="cart-item-info">
                            <h3>${item.name}</h3>
                            <p>${item.price} €</p>
                            <div class="quantity-controls">
                                <button onclick="updateQuantity(${item.product_id}, ${item.quantity - 1})">-</button>
                                <span>${item.quantity}</span>
                                <button onclick="updateQuantity(${item.product_id}, ${item.quantity + 1})">+</button>
                            </div>
                            <button class="btn-small btn-danger" onclick="removeFromCart(${item.product_id})">Supprimer</button>
                        </div>
                    `;
                    container.appendChild(cartItem);
                    total += item.price * item.quantity;
                });

                document.getElementById('cart-total').textContent = `Total: ${total.toFixed(2)} €`;
            });
    }

    function updateQuantity(productId, newQuantity) {
        if (newQuantity < 1) {
            removeFromCart(productId);
            return;
        }

        fetch('update_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: newQuantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadCart();
            }
        });
    }

    function removeFromCart(productId) {
        fetch('remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadCart();
            }
        });
    }

    function checkout() {
        fetch('checkout.php', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Commande passée avec succès!');
                loadCart();
            }
        });
    }
    </script>
</body>
</html>
