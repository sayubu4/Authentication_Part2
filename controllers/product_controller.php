<?php
// Include the product class
require_once(__DIR__ . '/../classes/product_class.php');

/* ---------------------------- CREATE ---------------------------- */
// Add product controller
function add_product_ctr($cat_id, $brand_id, $title, $price, $desc, $image, $keywords, $region_id, $opening_hours = null, $contact_phone = null, $exact_location = null) {
    $product = new Product();
    return $product->add_product($cat_id, $brand_id, $title, $price, $desc, $image, $keywords, $region_id, $opening_hours, $contact_phone, $exact_location);
}

/* ---------------------------- READ ---------------------------- */
// Get single product by ID
function get_product_by_id_ctr($product_id) {
    $product = new Product();
    return $product->get_product_by_id($product_id);
}

// View single product (alias for readability)
function view_single_product_ctr($id) {
    $product = new Product();
    return $product->view_single_product($id);
}

// Get all products
function get_all_products_ctr() {
    $product = new Product();
    return $product->get_all_products();
}

// View all products (same as get_all)
function view_all_products_ctr() {
    $product = new Product();
    return $product->view_all_products();
}

/* ---------------------------- SEARCH & FILTER ---------------------------- */
// Search products by title or keyword
function search_products_ctr($query) {
    $product = new Product();
    return $product->search_products($query);
}

// Filter products by category
function filter_products_by_category_ctr($cat_id) {
    $product = new Product();
    return $product->filter_products_by_category($cat_id);
}

// Filter products by brand
function filter_products_by_brand_ctr($brand_id) {
    $product = new Product();
    return $product->filter_products_by_brand($brand_id);
}

// Composite filter — supports category, brand, keyword, price range, etc.
function filter_products_ctr($filters = []) {
    $product = new Product();
    return $product->filter_products($filters);
}

/* ---------------------------- PAGINATION SUPPORT ---------------------------- */
// Get paginated products (10 per page, default)
function get_paginated_products_ctr($page = 1, $limit = 10) {
    $product = new Product();
    return $product->get_paginated_products($page, $limit);
}

/* ---------------------------- UPDATE ---------------------------- */
// Update product
function update_product_ctr($product_id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords, $region_id, $opening_hours = null, $contact_phone = null, $exact_location = null) {
    $product = new Product();
    return $product->update_product($product_id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords, $region_id, $opening_hours, $contact_phone, $exact_location);
}

/* ---------------------------- DELETE ---------------------------- */
// Delete product
function delete_product_ctr($product_id) {
    $product = new Product();
    return $product->delete_product($product_id);
}

/* ---------------------------- LEGACY SUPPORT ---------------------------- */
// Get products by category (for backward compatibility)
function get_products_by_category_ctr($cat_id) {
    $product = new Product();
    return $product->get_products_by_category($cat_id);
}

// Get products by brand (for backward compatibility)
function get_products_by_brand_ctr($brand_id) {
    $product = new Product();
    return $product->get_products_by_brand($brand_id);
}

// Get products by region
function get_products_by_region_ctr($region_id) {
    $product = new Product();
    return $product->get_products_by_region($region_id);
}
?>
