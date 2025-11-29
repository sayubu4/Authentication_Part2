<?php
require_once(__DIR__ . '/../settings/core.php');
require_once(__DIR__ . '/../controllers/cart_controller.php');
require_once(__DIR__ . '/../controllers/order_controller.php');
require_once(__DIR__ . '/../controllers/product_controller.php');

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Login required']);
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Fetch cart items
$items = get_user_cart_ctr($customer_id);
if (!$items || count($items) === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Wishlist is empty']);
    exit;
}

// Calculate total from backend prices
$total = 0.0;
foreach ($items as $it) {
    $price = (float)($it['product_price'] ?? 0);
    $qty = (int)($it['qty'] ?? 1);
    $total += ($price * $qty);
}

$invoice_no = rand(100000, 999999);
$order_date = date('Y-m-d');
$order_status = 'Confirmed'; // Simulated reservation confirmation

// Create order
$order_id = create_order_ctr($customer_id, $invoice_no, $order_date, $order_status);
if (!$order_id) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to create order']);
    exit;
}

// Insert order details
foreach ($items as $it) {
    $ok = add_order_details_ctr($order_id, (int)$it['product_id'], (int)$it['qty']);
    if (!$ok) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add order details']);
        exit;
    }
}

// Record simulated payment
$currency = 'FBu';
$payment_ok = record_payment_ctr($total, $customer_id, $order_id, $currency, $order_date);
if (!$payment_ok) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to record payment']);
    exit;
}

// Empty cart
empty_cart_ctr($customer_id);

echo json_encode([
    'status' => 'success',
    'order_id' => $order_id,
    'invoice_no' => $invoice_no,
    'amount' => $total,
    'currency' => $currency,
    'message' => 'Reservation confirmed'
]);
