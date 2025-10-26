<?php
// Include the product class
require_once(__DIR__ . '/../classes/product_class.php');

// Add product controller
function add_product_ctr($cat_id, $brand_id, $title, $price, $desc, $image, $keywords) {
    $product = new Product();
    return $product->add_product($cat_id, $brand_id, $title, $price, $desc, $image, $keywords);
}

// Get product by ID controller
function get_product_by_id_ctr($product_id) {
    $product = new Product();
    return $product->get_product_by_id($product_id);
}

// Get all products controller
function get_all_products_ctr() {
    $product = new Product();
    return $product->get_all_products();
}

// Update product controller
function update_product_ctr($product_id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords) {
    $product = new Product();
    return $product->update_product($product_id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords);
}

// Delete product controller
function delete_product_ctr($product_id) {
    $product = new Product();
    return $product->delete_product($product_id);
}

// Get products by category controller
function get_products_by_category_ctr($cat_id) {
    $product = new Product();
    return $product->get_products_by_category($cat_id);
}

// Get products by brand controller
function get_products_by_brand_ctr($brand_id) {
    $product = new Product();
    return $product->get_products_by_brand($brand_id);
}
?>