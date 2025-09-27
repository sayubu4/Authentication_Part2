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
$category_name = isset($_POST['category_name']) ? trim($_POST['category_name']) : '';

if (empty($category_name)) {
    $response['status'] = 'error';
    $response['message'] = 'Category name is required';
    echo json_encode($response);
    exit();
}

// Validate category name length
if (strlen($category_name) > 100) {
    $response['status'] = 'error';
    $response['message'] = 'Category name must be less than 100 characters';
    echo json_encode($response);
    exit();
}

try {
    $user_id = $_SESSION['customer_id'];
    $result = add_category_ctr($category_name, $user_id);
    
    if ($result === false) {
        $response['status'] = 'error';
        $response['message'] = 'Category name already exists';
    } else {
        $response['status'] = 'success';
        $response['message'] = 'Category added successfully';
        $response['category_id'] = $result;
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Failed to add category: ' . $e->getMessage();
}

echo json_encode($response);
?>