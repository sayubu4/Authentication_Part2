<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering to catch any unwanted output
ob_start();

// Debug: Log request details
error_log("=== Login Action Started ===");
error_log("REQUEST_METHOD: " . (isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'undefined'));
error_log("POST data: " . print_r($_POST, true));

// Set JSON header
header('Content-Type: application/json');

// Start or resume session with enhanced settings
session_name('taste_of_africa');
$domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
$secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';

session_set_cookie_params([
    'lifetime' => 86400, // 24 hours
    'path' => '/',
    'domain' => $domain,
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax'
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_log("Session started. Session ID: " . session_id());

$response = array();

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
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Basic validation
if (empty($email) || empty($password)) {
    $response['status'] = 'error';
    $response['message'] = 'Email and password are required';
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
    $login_result = login_customer_ctr($email, $password);
    
    if ($login_result === "invalid_credentials") {
        $response['status'] = 'error';
        $response['message'] = 'Invalid email or password';
    } elseif ($login_result === "user_not_found") {
        $response['status'] = 'error';
        $response['message'] = 'User not found';
    } elseif (is_array($login_result)) {
        // Login successful - set session variables
        $_SESSION['customer_id'] = $login_result['customer_id'];
        $_SESSION['customer_name'] = $login_result['customer_name'];
        $_SESSION['customer_email'] = $login_result['customer_email'];
        $_SESSION['user_role'] = $login_result['user_role'];
        $_SESSION['customer_country'] = $login_result['customer_country'];
        $_SESSION['customer_city'] = $login_result['customer_city'];
        
        $response = [
            'status' => 'success',
            'message' => 'Login successful',
            'user' => [
                'name' => $login_result['customer_name'],
                'email' => $login_result['customer_email'],
                'role' => $login_result['user_role']
            ]
        ];
        
        // Check if there's a stored redirect URL from before login
        if (isset($_SESSION['redirect_after_login']) && !empty($_SESSION['redirect_after_login'])) {
            $response['redirect_url'] = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
        } else {
            // Set redirect URL - both admin and customer go to index.php
            // index.php shows different content based on user role
            $response['redirect_url'] = '../index.php';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Login failed';
    }
    
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Login failed: ' . $e->getMessage();
}

// Clean any output buffer and send JSON response
ob_clean();
echo json_encode($response);
exit();
?>
