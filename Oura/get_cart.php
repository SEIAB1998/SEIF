<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$userId = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("
        SELECT c.*, p.name, p.price, p.image_path 
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
    ");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($cartItems);
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    exit(json_encode(['success' => false, 'message' => $e->getMessage()]));
}
?>