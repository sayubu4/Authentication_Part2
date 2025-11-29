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

    // Get form data
    $product_id = isset($_POST['product_id']) ? trim($_POST['product_id']) : '';
    $cat_id = isset($_POST['product_cat']) ? trim($_POST['product_cat']) : '';
    $brand_id = isset($_POST['product_brand']) ? trim($_POST['product_brand']) : '';
    $title = isset($_POST['product_title']) ? trim($_POST['product_title']) : '';
    $price = isset($_POST['product_price']) ? trim($_POST['product_price']) : '';
    $desc = isset($_POST['product_desc']) ? trim($_POST['product_desc']) : '';
    $keywords = isset($_POST['product_keywords']) ? trim($_POST['product_keywords']) : '';
    $image_path = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';
    $region_id = isset($_POST['region_id']) ? trim($_POST['region_id']) : '';
    $opening_hours = isset($_POST['opening_hours']) ? trim($_POST['opening_hours']) : null;
    $contact_phone = isset($_POST['contact_phone']) ? trim($_POST['contact_phone']) : null;
    $exact_location = isset($_POST['exact_location']) ? trim($_POST['exact_location']) : null;
    
    // Validate required fields
    if (empty($product_id) || empty($cat_id) || empty($brand_id) || empty($region_id) || empty($title) || empty($price) || empty($desc) || empty($keywords)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit();
    }
    
    // Validate price
    if (!is_numeric($price) || floatval($price) <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid price']);
        exit();
    }
    
    try {
        $result = update_product_ctr($product_id, $cat_id, $brand_id, $title, $price, $desc, $image_path, $keywords, $region_id, $opening_hours, $contact_phone, $exact_location);
        ob_clean();
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Product updated successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update product']);
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