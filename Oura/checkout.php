<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$userId = $_SESSION['user_id'];

try {
    $pdo->beginTransaction();
    
    // Get cart items
    $stmt = $pdo->prepare("
        SELECT c.*, p.price 
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
    ");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($cartItems)) {
        throw new Exception("Cart is empty");
    }
    
    // Calculate total
    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    
    // Create order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
    $stmt->execute([$userId, $total]);
    $orderId = $pdo->lastInsertId();
    
    // Add order items
    foreach ($cartItems as $item) {
        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
    }
    
    // Clear cart
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$userId]);
    
    $pdo->commit();
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    header("HTTP/1.1 500 Internal Server Error");
    exit(json_encode(['success' => false, 'message' => $e->getMessage()]));
}
?>