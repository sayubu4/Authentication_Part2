<?php
require_once(__DIR__ . '/../settings/core.php');
require_once(__DIR__ . '/../controllers/cart_controller.php');

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Login required']);
    exit;
}

$customer_id = $_SESSION['customer_id'] ?? null;
$ok = empty_cart_ctr($customer_id);
if ($ok) {
    echo json_encode(['status' => 'success', 'message' => 'Wishlist emptied']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to empty wishlist']);
}
