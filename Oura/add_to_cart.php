<?php
session_start();
header('Content-Type: application/json');
require_once 'config.php';

// 1) Authentication check
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized'
    ]);
    exit;
}

// 2) Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method Not Allowed'
    ]);
    exit;
}

// 3) Validate inputs
$userId    = $_SESSION['user_id'];
$productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$quantity  = filter_input(
    INPUT_POST,
    'quantity',
    FILTER_VALIDATE_INT,
    ['options' => ['default' => 1, 'min_range' => 1]]
);

if ($productId === false || $productId <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid product ID'
    ]);
    exit;
}

try {
    // 4) Check for existing cart item
    $stmt = $pdo->prepare(
        "SELECT id, quantity 
         FROM cart 
         WHERE user_id = :uid 
           AND product_id = :pid"
    );
    $stmt->execute([
        ':uid' => $userId,
        ':pid' => $productId
    ]);

    if ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // 5a) Update quantity
        $newQty = $item['quantity'] + $quantity;
        $upd = $pdo->prepare(
            "UPDATE cart 
             SET quantity = :qty 
             WHERE id = :id"
        );
        $upd->execute([
            ':qty' => $newQty,
            ':id'  => $item['id']
        ]);
    } else {
        // 5b) Insert new row
        $ins = $pdo->prepare(
            "INSERT INTO cart (user_id, product_id, quantity) 
             VALUES (:uid, :pid, :qty)"
        );
        $ins->execute([
            ':uid' => $userId,
            ':pid' => $productId,
            ':qty' => $quantity
        ]);
    }

    // 6) (Optional) fetch updated total items in cart
    $countStmt = $pdo->prepare(
        "SELECT COALESCE(SUM(quantity),0) AS total 
         FROM cart 
         WHERE user_id = ?"
    );
    $countStmt->execute([$userId]);
    $total = (int)$countStmt->fetchColumn();

    // 7) Success response
    echo json_encode([
        'success'    => true,
        'message'    => 'Produit ajouté au panier',
        'cart_count' => $total
    ]);
    exit;

} catch (PDOException $e) {
    // 8) Error handling
    http_response_code(500);
    echo json_encode([
        'success' => false,
        // don't expose $e->getMessage() in production
        'message' => 'Server error, veuillez réessayer plus tard'
    ]);
    exit;
}
