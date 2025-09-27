<?php
require_once '../classes/category_class.php';

// Add category controller
function add_category_ctr($name, $customer_id)
{
    $category = new Category();
    return $category->add($name, $customer_id);
}

// Get categories by user controller
function get_categories_by_user_ctr($customer_id)
{
    $category = new Category();
    return $category->getCategoriesByUser($customer_id);
}

// Get category by ID controller
function get_category_by_id_ctr($cat_id, $customer_id)
{
    $category = new Category();
    return $category->getCategoryById($cat_id, $customer_id);
}

// Update category controller
function update_category_ctr($cat_id, $name, $customer_id)
{
    $category = new Category();
    return $category->updateCategory($cat_id, $name, $customer_id);
}

// Delete category controller
function delete_category_ctr($cat_id, $customer_id)
{
    $category = new Category();
    return $category->deleteCategory($cat_id, $customer_id);
}

// Get all categories controller (for public display)
function get_all_categories_ctr()
{
    $category = new Category();
    return $category->getAllCategories();
}
?>