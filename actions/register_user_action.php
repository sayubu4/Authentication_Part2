<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering to catch any unwanted output
ob_start();

// Debug: Log request details
error_log("Registration action called. REQUEST_METHOD: " . (isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'undefined'));
error_log("POST data: " . print_r($_POST, true));

header('Content-Type: application/json');

session_start();

$response = array();

// Note: Registration should be allowed even if user is logged in
// (user might want to create another account or register someone else)

// Check if request method is POST
if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method. Expected POST, got: ' . (isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'undefined');
    echo json_encode($response);
    exit();
}

// Include the controller with error handling
try {
    require_once '../controllers/user_controller.php';
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Failed to load controller: ' . $e->getMessage();
    echo json_encode($response);
    exit();
}

// Get POST data and validate
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$phone_number = isset($_POST['phone_number']) ? trim($_POST['phone_number']) : '';
$country = isset($_POST['country']) ? trim($_POST['country']) : '';
$city = isset($_POST['city']) ? trim($_POST['city']) : '';
$role = isset($_POST['role']) ? intval($_POST['role']) : 1; // Default to customer (1), admin is 2

// Basic validation
if (empty($name) || empty($email) || empty($password) || empty($phone_number) || empty($country) || empty($city)) {
    $response['status'] = 'error';
    $response['message'] = 'All fields are required';
    echo json_encode($response);
    exit();
}

// Email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['status'] = 'error';
    $response['message'] = 'Invalid email format';
    echo json_encode($response);
    exit();
}

try {
    $customer_id = register_user_ctr($name, $email, $password, $phone_number, $country, $city, $role);
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Registration failed: ' . $e->getMessage();
    echo json_encode($response);
    exit();
}

if ($customer_id === "email_exists") {
    $response['status'] = 'error';
    $response['message'] = 'Email already in use';
} elseif ($customer_id) {
    $response['status'] = 'success';
    $response['message'] = 'Registered successfully';
    $response['customer_id'] = $customer_id;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Failed to register';
}

// Clean any output buffer and send JSON response
ob_clean();
echo json_encode($response);
exit();
?>