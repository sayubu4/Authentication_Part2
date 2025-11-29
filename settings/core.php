<?php
// Start session with consistent settings
if (session_status() === PHP_SESSION_NONE) {
    session_name('taste_of_africa');
    $domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    
    session_set_cookie_params([
        'lifetime' => 86400, // 24 hours
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    
    session_start();
}

//for header redirection
ob_start();

/**
 * Function to check if user is logged in
 * @param string $redirect_url URL to redirect to if not logged in
 * @return bool Returns true if user is logged in, false otherwise
 */
function require_login($redirect_url = 'login.php') {
    // Get the current page filename
    $current_page = basename($_SERVER['PHP_SELF']);
    
    // If user is not logged in and not already on the login page
    if (!isset($_SESSION['customer_id']) && $current_page !== 'login.php') {
        // Store the current URL in session for redirecting back after login
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        
        // Prevent redirect loops by checking if we're already going to login page
        if (strpos($redirect_url, 'login.php') === false) {
            header("Location: " . $redirect_url);
            exit();
        }
        return false;
    }
    
    // If user is logged in but on the login page, redirect to home
    if (isset($_SESSION['customer_id']) && $current_page === 'login.php') {
        $redirect = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : '../index.php';
        unset($_SESSION['redirect_after_login']);
        header("Location: " . $redirect);
        exit();
    }
    
    return true;
}

function isLoggedIn(){
    if (!isset($_SESSION['customer_id'])){
        return false;
}  
else{
    return true;
} 

}

//function to check for role (admin, customer, etc)

function isAdmin(){
    if (isLoggedIn()) {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 2;
    }
    return false;
}

// Helper: get current logged in user's ID (used by payment/subscription code)
function get_user_id() {
    return isset($_SESSION['customer_id']) ? (int)$_SESSION['customer_id'] : null;
}

// Helper: get current logged in user's display name (fallback if not set)
function get_user_name() {
    if (isset($_SESSION['customer_name']) && $_SESSION['customer_name'] !== '') {
        return $_SESSION['customer_name'];
    }
    if (isset($_SESSION['customer_email']) && $_SESSION['customer_email'] !== '') {
        return $_SESSION['customer_email'];
    }
    return 'Customer';
}

if (!function_exists('log_user_activity')) {
    /**
     * Basic activity logger used around the app (currently logs to error log).
     */
    function log_user_activity($message) {
        $customer = isset($_SESSION['customer_id']) ? 'User #' . $_SESSION['customer_id'] : 'Guest';
        error_log("[Activity] {$customer} - {$message}");
    }
}


?>