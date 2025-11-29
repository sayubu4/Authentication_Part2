<?php
require_once '../settings/core.php';
require_once '../controllers/category_controller.php';

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
    $user_id = $_SESSION['customer_id'];
    $categories = get_categories_by_user_ctr($user_id);
    
    $response['status'] = 'success';
    $response['categories'] = $categories;
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Failed to fetch categories: ' . $e->getMessage();
}

echo json_encode($response);
?>