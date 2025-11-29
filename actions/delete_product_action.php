<?php
// Start session with consistent settings
require_once(__DIR__ . '/../settings/core.php');

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
ini_set('display_errors', 0);
ob_start();

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Include necessary files
require_once(__DIR__ . '/../controllers/product_controller.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get product ID
    $product_id = isset($_POST['product_id']) ? trim($_POST['product_id']) : '';
    
    // Validate product ID
    if (empty($product_id)) {
        echo json_encode(['success' => false, 'message' => 'Product ID is required']);
        exit();
    }
    
    try {
        $result = delete_product_ctr($product_id);
        ob_clean();
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Product deleted successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete product']);
        }
    } catch (Throwable $e) {
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Exception: ' . $e->getMessage()]);
    }
} else {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>

