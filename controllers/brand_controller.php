<?php
require_once '../classes/brand_class.php';

// Add brand controller
function add_brand_ctr($name, $category_id)
{
    $brand = new Brand();
    return $brand->add($name, $category_id);
}

// Get all brands controller
function get_all_brands_ctr()
{
    $brand = new Brand();
    return $brand->getAllBrands();
}

// Get brand by ID controller
function get_brand_by_id_ctr($brand_id)
{
    $brand = new Brand();
    return $brand->getBrandById($brand_id);
}

// Update brand controller
function update_brand_ctr($brand_id, $name, $category_id)
{
    $brand = new Brand();
    return $brand->updateBrand($brand_id, $name, $category_id);
}

// Delete brand controller
function delete_brand_ctr($brand_id)
{
    $brand = new Brand();
    return $brand->deleteBrand($brand_id);
}
?>
