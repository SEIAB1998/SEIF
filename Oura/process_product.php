<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    
    // Handle file upload
    $imagePath = '';
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
        $stmt = $pdo->prepare("INSERT INTO products (user_id, name, description, price, category, image_path) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $name, $description, $price, $category, $imagePath]);
        
        header("Location: dashboard.php?success=product_added");
        exit();
    } catch (PDOException $e) {
        die("Product upload failed: " . $e->getMessage());
    }
}
?>