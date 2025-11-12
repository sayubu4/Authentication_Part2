<?php
require_once(__DIR__ . '/../settings/core.php');
require_once(__DIR__ . '/../controllers/cart_controller.php');

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Login required']);
    exit;
}

$customer_id = $_SESSION['customer_id'] ?? null;
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if (!$product_id) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product']);
    exit;
}

$ok = remove_from_cart_ctr($customer_id, $product_id);
if ($ok) {
    echo json_encode(['status' => 'success', 'message' => 'Removed from wishlist']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to remove']);
}
