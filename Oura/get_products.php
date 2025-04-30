<?php
require_once 'config.php';

header('Content-Type: application/json');

try {
    $search = isset($_GET['search']) ? "%{$_GET['search']}%" : '%';

    // Debug info
    error_log("Search term: " . $search);

    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY created_at DESC");
    $stmt->execute([$search, $search]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug info
    error_log("Found " . count($products) . " products");

    echo json_encode($products);
} catch (PDOException $e) {
    error_log("Error fetching products: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch products: ' . $e->getMessage()]);
}
?>