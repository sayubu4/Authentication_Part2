<?php
require_once '../settings/core.php';
require_once '../controllers/brand_controller.php';

header('Content-Type: application/json');

$response = array();

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    $response['status'] = 'error';
    $response['message'] = 'Access denied. Admin privileges required.';
    echo json_encode($response);
    exit();
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit();
}

// Get and validate input
$brand_name = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : '';
$category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;

if (empty($brand_name)) {
    $response['status'] = 'error';
    $response['message'] = 'Brand name is required';
    echo json_encode($response);
    exit();
}

if ($category_id <= 0) {
    $response['status'] = 'error';
    $response['message'] = 'Please select a valid category';
    echo json_encode($response);
    exit();
}

// Validate brand name length
if (strlen($brand_name) > 100) {
    $response['status'] = 'error';
    $response['message'] = 'Brand name must be less than 100 characters';
    echo json_encode($response);
    exit();
}

try {
    $result = add_brand_ctr($brand_name, $category_id);
    
    if ($result === false) {
        $response['status'] = 'error';
        $response['message'] = 'Brand name already exists';
    } else {
        $response['status'] = 'success';
        $response['message'] = 'Brand added successfully';
        $response['brand_id'] = $result;
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Failed to add brand: ' . $e->getMessage();
}

echo json_encode($response);
?>
