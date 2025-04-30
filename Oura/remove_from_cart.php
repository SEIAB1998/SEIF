<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$input = json_decode(file_get_contents('php://input'), true);
$userId = $_SESSION['user_id'];
$productId = $input['product_id'];

try {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$userId, $productId]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    exit(json_encode(['success' => false, 'message' => $e->getMessage()]));
}
?>