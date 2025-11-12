<?php
require_once(__DIR__ . '/../classes/cart_class.php');

function add_to_cart_ctr($customer_id, $product_id, $qty = 1) {
    $cart = new Cart();
    return $cart->add_to_cart($customer_id, $product_id, $qty);
}

function update_cart_item_ctr($customer_id, $product_id, $qty) {
    $cart = new Cart();
    return $cart->update_cart_item($customer_id, $product_id, $qty);
}

function remove_from_cart_ctr($customer_id, $product_id) {
    $cart = new Cart();
    return $cart->remove_from_cart($customer_id, $product_id);
}

function get_user_cart_ctr($customer_id) {
    $cart = new Cart();
    return $cart->get_user_cart($customer_id);
}

function empty_cart_ctr($customer_id) {
    $cart = new Cart();
    return $cart->empty_cart($customer_id);
}
