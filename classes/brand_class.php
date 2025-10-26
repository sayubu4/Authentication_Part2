<?php
require_once '../settings/db_class.php';

class Brand extends db_connection
{
    public $db;

    public function __construct()
    {
        try {
            $this->db = $this->db_conn();
            if (!$this->db) {
                error_log("Database connection failed in Brand constructor");
                throw new Exception("Database connection failed");
            }
        } catch (Exception $e) {
            error_log("Database connection error in Brand constructor: " . $e->getMessage());
            throw new Exception("Failed to connect to database: " . $e->getMessage());
        }
    }

    // Add a new brand
    public function add($name, $category_id)
    {
        // Check if brand name already exists
        $checkStmt = $this->db->prepare("SELECT * FROM brands WHERE brand_name = ?");
        $checkStmt->bind_param("s", $name);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            return false; // Brand name exists
        }

        // Try to add category_id column if it doesn't exist
        $this->ensureCategoryIdColumn();

        // Insert new brand
        $stmt = $this->db->prepare("INSERT INTO brands (brand_name, category_id) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $category_id);

        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    // Ensure category_id column exists
    private function ensureCategoryIdColumn()
    {
        $result = $this->db->query("SHOW COLUMNS FROM brands LIKE 'category_id'");
        if ($result->num_rows == 0) {
            $this->db->query("ALTER TABLE brands ADD COLUMN category_id int(11) DEFAULT 0");
        }
    }

    // Get all brands
    public function getAllBrands()
    {
        // Ensure category_id column exists
        $this->ensureCategoryIdColumn();
        
        $stmt = $this->db->prepare("
            SELECT b.*, c.cat_name 
            FROM brands b 
            LEFT JOIN categories c ON b.category_id = c.cat_id 
            ORDER BY c.cat_name ASC, b.brand_name ASC
        ");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get a specific brand by ID
    public function getBrandById($brand_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM brands WHERE brand_id = ?");
        $stmt->bind_param("i", $brand_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Update a brand
    public function updateBrand($brand_id, $name, $category_id)
    {
        // Ensure category_id column exists
        $this->ensureCategoryIdColumn();
        
        // Check if brand name already exists (excluding current brand)
        $checkStmt = $this->db->prepare("SELECT * FROM brands WHERE brand_name = ? AND brand_id != ?");
        $checkStmt->bind_param("si", $name, $brand_id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            return false; // Brand name exists
        }

        // Update brand
        $stmt = $this->db->prepare("UPDATE brands SET brand_name = ?, category_id = ? WHERE brand_id = ?");
        $stmt->bind_param("sii", $name, $category_id, $brand_id);
        
        return $stmt->execute();
    }

    // Delete a brand
    public function deleteBrand($brand_id)
    {
        $stmt = $this->db->prepare("DELETE FROM brands WHERE brand_id = ?");
        $stmt->bind_param("i", $brand_id);
        
        return $stmt->execute();
    }
}
?>
