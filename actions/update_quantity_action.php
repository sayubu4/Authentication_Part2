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
$qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1;

if (!$product_id || $qty < 1) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
    exit;
}

$ok = update_cart_item_ctr($customer_id, $product_id, $qty);
if ($ok) {
    echo json_encode(['status' => 'success', 'message' => 'Quantity updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update']);
}
