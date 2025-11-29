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

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit();
}

// Get and validate input
$category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;

if ($category_id <= 0) {
    $response['status'] = 'error';
    $response['message'] = 'Invalid category ID';
    echo json_encode($response);
    exit();
}

try {
    $user_id = $_SESSION['customer_id'];
    $result = delete_category_ctr($category_id, $user_id);
    
    if ($result) {
        $response['status'] = 'success';
        $response['message'] = 'Category deleted successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Category not found or could not be deleted';
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Failed to delete category: ' . $e->getMessage();
}

echo json_encode($response);
?>