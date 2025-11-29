<?php
// Include the database connection class
require_once(__DIR__ . '/../settings/db_class.php');

class Product extends db_connection {
    
    /* ---------------------------- CREATE ---------------------------- */
    // Add a new product
    public function add_product($cat_id, $brand_id, $title, $price, $desc, $image, $keywords, $region_id, $opening_hours = null, $contact_phone = null, $exact_location = null) {
        $cat_id = $this->db_escape($cat_id);
        $brand_id = $this->db_escape($brand_id);
        $title = $this->db_escape($title);
        $price = $this->db_escape($price);
        $desc = $this->db_escape($desc);
        $image = $this->db_escape($image);
        $keywords = $this->db_escape($keywords);
        $region_id = $this->db_escape($region_id);
        $opening_hours = $this->db_escape($opening_hours);
        $contact_phone = $this->db_escape($contact_phone);
        $exact_location = $this->db_escape($exact_location);

        $sql = "INSERT INTO products (product_cat, product_brand, product_title, product_price, 
                product_desc, product_image, product_keywords, region_id, opening_hours, contact_phone, exact_location) 
                VALUES ('$cat_id', '$brand_id', '$title', '$price', '$desc', '$image', '$keywords', '$region_id', '$opening_hours', '$contact_phone', '$exact_location')";
        return $this->db_query($sql);
    }

    /* ---------------------------- READ ---------------------------- */
    // Get product by ID
    public function get_product_by_id($product_id) {
        $product_id = $this->db_escape($product_id);
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

    // Get all products for a specific region
    public function get_products_by_region($region_id) {
        $region_id = $this->db_escape($region_id);
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.region_id = '$region_id'
                ORDER BY p.product_id DESC";
        return $this->db_fetch_all($sql);
    }

    // View all products (same as get_all_products)
    public function view_all_products() {
        return $this->get_all_products();
    }

    // View single product
    public function view_single_product($id) {
        return $this->get_product_by_id($id);
    }

    /* ---------------------------- SEARCH ---------------------------- */
    // Search products by title or keywords
    public function search_products($query) {
        $query = $this->db_escape($query);
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_title LIKE '%$query%'
                   OR p.product_keywords LIKE '%$query%'
                ORDER BY p.product_id DESC";
        return $this->db_fetch_all($sql);
    }

    /* ---------------------------- FILTERS ---------------------------- */
    // Filter by category
    public function filter_products_by_category($cat_id) {
        $cat_id = $this->db_escape($cat_id);
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_cat = '$cat_id'
                ORDER BY p.product_id DESC";
        return $this->db_fetch_all($sql);
    }

    // Filter by brand
    public function filter_products_by_brand($brand_id) {
        $brand_id = $this->db_escape($brand_id);
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_brand = '$brand_id'
                ORDER BY p.product_id DESC";
        return $this->db_fetch_all($sql);
    }

    // Composite filter (category, brand, price, keyword)
    public function filter_products($filters = []) {
        $conditions = [];

        if (!empty($filters['cat_id'])) {
            $conditions[] = "p.product_cat = '" . $this->db_escape($filters['cat_id']) . "'";
        }
        if (!empty($filters['brand_id'])) {
            $conditions[] = "p.product_brand = '" . $this->db_escape($filters['brand_id']) . "'";
        }
        if (!empty($filters['min_price'])) {
            $conditions[] = "p.product_price >= '" . $this->db_escape($filters['min_price']) . "'";
        }
        if (!empty($filters['max_price'])) {
            $conditions[] = "p.product_price <= '" . $this->db_escape($filters['max_price']) . "'";
        }
        if (!empty($filters['keyword'])) {
            $keyword = $this->db_escape($filters['keyword']);
            $conditions[] = "(p.product_title LIKE '%$keyword%' OR p.product_keywords LIKE '%$keyword%')";
        }

        $where = count($conditions) > 0 ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                $where
                ORDER BY p.product_price ASC";
        return $this->db_fetch_all($sql);
    }

    /* ---------------------------- PAGINATION ---------------------------- */
    // Fetch paginated products
    public function get_paginated_products($page = 1, $limit = 10) {
        $page = (int)$page;
        $limit = (int)$limit;
        $offset = ($page - 1) * $limit;

        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                ORDER BY p.product_id DESC
                LIMIT $limit OFFSET $offset";
        return $this->db_fetch_all($sql);
    }

    // Count all products (for pagination)
    public function count_all_products() {
        $sql = "SELECT COUNT(*) AS total FROM products";
        $result = $this->db_fetch_one($sql);
        return $result ? $result['total'] : 0;
    }

    /* ---------------------------- UPDATE ---------------------------- */
    public function update_product($product_id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords, $region_id, $opening_hours = null, $contact_phone = null, $exact_location = null) {
        $product_id = $this->db_escape($product_id);
        $cat_id = $this->db_escape($cat_id);
        $brand_id = $this->db_escape($brand_id);
        $title = $this->db_escape($title);
        $price = $this->db_escape($price);
        $desc = $this->db_escape($desc);
        $image = $this->db_escape($image);
        $keywords = $this->db_escape($keywords);
        $region_id = $this->db_escape($region_id);
        $opening_hours = $this->db_escape($opening_hours);
        $contact_phone = $this->db_escape($contact_phone);
        $exact_location = $this->db_escape($exact_location);

        if (empty($image)) {
            $sql = "UPDATE products SET 
                    product_cat = '$cat_id',
                    product_brand = '$brand_id',
                    product_title = '$title',
                    product_price = '$price',
                    product_desc = '$desc',
                    product_keywords = '$keywords',
                    region_id = '$region_id',
                    opening_hours = '$opening_hours',
                    contact_phone = '$contact_phone',
                    exact_location = '$exact_location'
                    WHERE product_id = '$product_id'";
        } else {
            $sql = "UPDATE products SET 
                    product_cat = '$cat_id',
                    product_brand = '$brand_id',
                    product_title = '$title',
                    product_price = '$price',
                    product_desc = '$desc',
                    product_image = '$image',
                    product_keywords = '$keywords',
                    region_id = '$region_id',
                    opening_hours = '$opening_hours',
                    contact_phone = '$contact_phone',
                    exact_location = '$exact_location'
                    WHERE product_id = '$product_id'";
        }
        return $this->db_query($sql);
    }

    /* ---------------------------- DELETE ---------------------------- */
    public function delete_product($product_id) {
        $product_id = $this->db_escape($product_id);
        $sql = "DELETE FROM products WHERE product_id = '$product_id'";
        return $this->db_query($sql);
    }

    /* ---------------------------- CATEGORY & BRAND GETTERS ---------------------------- */
    public function get_products_by_category($cat_id) {
        return $this->filter_products_by_category($cat_id);
    }

    public function get_products_by_brand($brand_id) {
        return $this->filter_products_by_brand($brand_id);
    }
}
?>
