<?php
session_start();
require_once 'config.php';

// Set content type to JSON
header('Content-Type: application/json');

// Enable error logging
error_log("Delete product request received");

if (!isset($_SESSION['user_id'])) {
    error_log("Unauthorized: No user_id in session");
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

// Get the input data
$input_raw = file_get_contents('php://input');
error_log("Raw input: " . $input_raw);

$input = json_decode($input_raw, true);
error_log("Decoded input: " . print_r($input, true));

$userId = $_SESSION['user_id'];
error_log("User ID: " . $userId);

// Check if product_id exists in the input
if (!isset($input['product_id'])) {
    error_log("No product_id in request");
    http_response_code(400);
    exit(json_encode(['success' => false, 'message' => 'Product ID is required']));
}

$productId = $input['product_id'];
error_log("Product ID: " . $productId);

try {
    // First check if the product belongs to the user
    $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ? AND user_id = ?");
    $stmt->execute([$productId, $userId]);

    if ($stmt->rowCount() === 0) {
        header("HTTP/1.1 403 Forbidden");
        exit(json_encode(['success' => false, 'message' => 'You do not have permission to delete this product']));
    }

    // Delete the product
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$productId]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    exit(json_encode(['success' => false, 'message' => $e->getMessage()]));
}
?>
