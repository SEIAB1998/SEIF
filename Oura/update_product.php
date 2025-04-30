<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $productId = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    
    // Check if the product belongs to the user
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND user_id = ?");
    $stmt->execute([$productId, $userId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        header("Location: dashboard.php?error=unauthorized");
        exit();
    }
    
    // Handle file upload
    $imagePath = $product['image_path']; // Keep existing image by default
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = $targetPath;
        }
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ?, image_path = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $category, $imagePath, $productId]);
        
        header("Location: dashboard.php?success=product_updated");
        exit();
    } catch (PDOException $e) {
        die("Product update failed: " . $e->getMessage());
    }
}
?>
