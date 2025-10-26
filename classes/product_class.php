<?php
// Include the database connection class
require_once(__DIR__ . '/../settings/db_class.php');

class Product extends db_connection {
    
    // Add a new product
    public function add_product($cat_id, $brand_id, $title, $price, $desc, $image, $keywords) {
        $sql = "INSERT INTO products (product_cat, product_brand, product_title, product_price, 
                product_desc, product_image, product_keywords) 
                VALUES ('$cat_id', '$brand_id', '$title', '$price', '$desc', '$image', '$keywords')";
        return $this->db_query($sql);
    }
    
    // Get product by ID
    public function get_product_by_id($product_id) {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_id = '$product_id'";
        return $this->db_fetch_one($sql);
    }
    
    // Get all products
    public function get_all_products() {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                ORDER BY p.product_id DESC";
        return $this->db_fetch_all($sql);
    }
    
    // Update product
    public function update_product($product_id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords) {
        // If image is empty, don't update it
        if (empty($image)) {
            $sql = "UPDATE products SET 
                    product_cat = '$cat_id',
                    product_brand = '$brand_id',
                    product_title = '$title',
                    product_price = '$price',
                    product_desc = '$desc',
                    product_keywords = '$keywords'
                    WHERE product_id = '$product_id'";
        } else {
            $sql = "UPDATE products SET 
                    product_cat = '$cat_id',
                    product_brand = '$brand_id',
                    product_title = '$title',
                    product_price = '$price',
                    product_desc = '$desc',
                    product_image = '$image',
                    product_keywords = '$keywords'
                    WHERE product_id = '$product_id'";
        }
        return $this->db_query($sql);
    }
    
    // Delete product
    public function delete_product($product_id) {
        $sql = "DELETE FROM products WHERE product_id = '$product_id'";
        return $this->db_query($sql);
    }
    
    // Get products by category
    public function get_products_by_category($cat_id) {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_cat = '$cat_id'
                ORDER BY p.product_id DESC";
        return $this->db_fetch_all($sql);
    }
    
    // Get products by brand
    public function get_products_by_brand($brand_id) {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_brand = '$brand_id'
                ORDER BY p.product_id DESC";
        return $this->db_fetch_all($sql);
    }
}
?>