<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_name('taste_of_africa');
    session_start();
}

// Check if user is logged in and is admin
if (!isset($_SESSION['customer_id'], $_SESSION['user_role']) || $_SESSION['user_role'] != 2) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Set headers for JSON response
header('Content-Type: application/json');

// Check if file was uploaded without errors
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    exit();
}

$file = $_FILES['image'];

// Validate file type
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($file['type'], $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, GIF, and WebP images are allowed']);
    exit();
}

// Validate file size (max 5MB)
$max_size = 5 * 1024 * 1024; // 5MB
if ($file['size'] > $max_size) {
    echo json_encode(['success' => false, 'message' => 'File size must be less than 5MB']);
    exit();
}

// Create uploads directory if it doesn't exist
$upload_dir = '../assets/images/products/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Generate unique filename
$file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'product_' . time() . '_' . uniqid() . '.' . $file_ext;
$filepath = $upload_dir . $filename;
$relative_path = 'assets/images/products/' . $filename;

// Move uploaded file
try {
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Return success with file path
        echo json_encode([
            'success' => true,
            'message' => 'Image uploaded successfully',
            'file_path' => $relative_path,
            'file_url' => $relative_path
        ]);
    } else {
        throw new Exception('Failed to move uploaded file');
    }
} catch (Exception $e) {
    error_log('Upload error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Failed to upload image. Please try again.'
    ]);
}
?>
