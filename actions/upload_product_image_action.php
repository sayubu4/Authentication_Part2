<?php
// Start session with consistent settings
require_once(__DIR__ . '/../settings/core.php');

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Check if image file is provided
if (!isset($_FILES['product_image']) || $_FILES['product_image']['error'] !== 0) {
    echo json_encode(['success' => false, 'message' => 'No image file uploaded or upload error occurred']);
    exit();
}

// Check if product_id is provided (for updates) or user_id (for new products)
$product_id = isset($_POST['product_id']) ? trim($_POST['product_id']) : 'temp_' . time();
$user_id = $_SESSION['customer_id'];

// Validate file type
$allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
$file_type = $_FILES['product_image']['type'];
$file_mime = mime_content_type($_FILES['product_image']['tmp_name']);

if (!in_array($file_type, $allowed_types) && !in_array($file_mime, $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Invalid image type. Only JPEG, PNG, and GIF are allowed']);
    exit();
}

// Check file size (5MB max)
if ($_FILES['product_image']['size'] > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'Image size exceeds 5MB limit']);
    exit();
}

// Construct the file path: uploads/u{user_id}/p{product_id}/ (school server mandates 'uploads/')
$upload_base = __DIR__ . '/../uploads/';

// Verify that uploads/ base directory exists (must be pre-created on server)
if (!is_dir($upload_base)) {
    echo json_encode(['success' => false, 'message' => "Required base directory 'uploads/' not found. Contact administrator."]);
    exit();
}

// Get real path of uploads/ directory for security verification
$real_upload_base = realpath($upload_base);
if ($real_upload_base === false) {
    echo json_encode(['success' => false, 'message' => "uploads directory path error. Contact administrator."]);
    exit();
}

// Create user directory: uploads/u{user_id}/
$user_dir = $upload_base . 'u' . $user_id . '/';

// Create product directory: uploads/u{user_id}/p{product_id}/
$product_dir = $user_dir . 'p' . $product_id . '/';

// Create directories if they don't exist
if (!file_exists($product_dir)) {
    if (!mkdir($product_dir, 0755, true)) {
        echo json_encode(['success' => false, 'message' => 'Failed to create upload directory']);
        exit();
    }
}

// Verify the created directory is inside uploads/ (security check)
$real_product_dir = realpath($product_dir);
if ($real_product_dir === false || strpos($real_product_dir, $real_upload_base) !== 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid upload path. Security violation detected.']);
    exit();
}

// Generate secure filename
$file_extension = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
$filename = 'image_' . time() . '_' . uniqid() . '.' . $file_extension;
$file_path = $product_dir . $filename;

// Final verification: ensure file path is inside uploads/
$real_file_dir = realpath(dirname($file_path));
if ($real_file_dir === false || strpos($real_file_dir, $real_upload_base) !== 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid file path. Upload rejected.']);
    exit();
}

// Move uploaded file to the constructed path
if (move_uploaded_file($_FILES['product_image']['tmp_name'], $file_path)) {
    // Construct relative path for database storage
    $relative_path = 'uploads/u' . $user_id . '/p' . $product_id . '/' . $filename;
    
    echo json_encode([
        'success' => true, 
        'message' => 'Image uploaded successfully',
        'image_path' => $relative_path,
        'product_id' => $product_id
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file to destination']);
}
?>