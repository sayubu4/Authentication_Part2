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
$brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : 0;

if ($brand_id <= 0) {
    $response['status'] = 'error';
    $response['message'] = 'Invalid brand ID';
    echo json_encode($response);
    exit();
}

try {
    $result = delete_brand_ctr($brand_id);
    
    if ($result === false) {
        $response['status'] = 'error';
        $response['message'] = 'Failed to delete brand or brand not found';
    } else {
        $response['status'] = 'success';
        $response['message'] = 'Brand deleted successfully';
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Failed to delete brand: ' . $e->getMessage();
}

echo json_encode($response);
?>
