<?php
require_once(__DIR__ . '/../classes/order_class.php');

function create_order_ctr($customer_id, $invoice_no, $order_date, $order_status) {
    $order = new Order();
    return $order->create_order($customer_id, $invoice_no, $order_date, $order_status);
}

function add_order_details_ctr($order_id, $product_id, $qty) {
    $order = new Order();
    return $order->add_order_detail($order_id, $product_id, $qty);
}

function record_payment_ctr($amount, $customer_id, $order_id, $currency, $payment_date) {
    $order = new Order();
    return $order->record_payment($amount, $customer_id, $order_id, $currency, $payment_date);
}

function get_user_orders_ctr($customer_id) {
    $order = new Order();
    return $order->get_orders_by_customer($customer_id);
}
