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

try {
    $brands = get_all_brands_ctr();
    
    $response['status'] = 'success';
    $response['brands'] = $brands;
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Failed to fetch brands: ' . $e->getMessage();
}

echo json_encode($response);
?>
