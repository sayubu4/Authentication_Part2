<?php
require_once '../settings/db_class.php';

class Category extends db_connection
{
    public $db;

    public function __construct()
    {
        try {
            $this->db = $this->db_conn();
            if (!$this->db) {
                error_log("Database connection failed in Category constructor");
                throw new Exception("Database connection failed");
            }
        } catch (Exception $e) {
            error_log("Database connection error in Category constructor: " . $e->getMessage());
            throw new Exception("Failed to connect to database: " . $e->getMessage());
        }
    }

    // Add a new category
    public function add($name, $customer_id)
    {
        // Check if category name already exists
        $checkStmt = $this->db->prepare("SELECT * FROM categories WHERE cat_name = ?");
        $checkStmt->bind_param("s", $name);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            return false; // Category name exists
        }

        // Insert new category
        $stmt = $this->db->prepare("INSERT INTO categories (cat_name) VALUES (?)");
        $stmt->bind_param("s", $name);

        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    // Get all categories created by a specific user
    public function getCategoriesByUser($customer_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY cat_name ASC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get a specific category by ID
    public function getCategoryById($cat_id, $customer_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE cat_id = ?");
        $stmt->bind_param("i", $cat_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Update a category
    public function updateCategory($cat_id, $name, $customer_id)
    {
        // Check if category name already exists (excluding current category)
        $checkStmt = $this->db->prepare("SELECT * FROM categories WHERE cat_name = ? AND cat_id != ?");
        $checkStmt->bind_param("si", $name, $cat_id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            return false; // Category name exists
        }

        // Update category
        $stmt = $this->db->prepare("UPDATE categories SET cat_name = ? WHERE cat_id = ?");
        $stmt->bind_param("si", $name, $cat_id);
        
        return $stmt->execute();
    }

    // Delete a category
    public function deleteCategory($cat_id, $customer_id)
    {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE cat_id = ?");
        $stmt->bind_param("i", $cat_id);
        
        return $stmt->execute();
    }

    // Get all categories (for public display)
    public function getAllCategories()
    {
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY cat_name ASC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>