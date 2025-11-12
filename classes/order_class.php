<?php
require_once(__DIR__ . '/../settings/db_class.php');

class Order extends db_connection {
    private $pdo;

    public function __construct() {
        $this->pdo = $this->db_conn();
    }

    // a. Create a new order and return its ID
    public function create_order($customer_id, $invoice_no, $order_date, $order_status) {
        $stmt = $this->pdo->prepare("INSERT INTO orders (customer_id, invoice_no, order_date, order_status) VALUES (?,?,?,?)");
        $stmt->bind_param('iiss', $customer_id, $invoice_no, $order_date, $order_status);
        if ($stmt->execute()) {
            return $this->pdo->insert_id;
        }
        return false;
    }

    // b. Add order details rows
    public function add_order_detail($order_id, $product_id, $qty) {
        $stmt = $this->pdo->prepare("INSERT INTO orderdetails (order_id, product_id, qty) VALUES (?,?,?)");
        $stmt->bind_param('iii', $order_id, $product_id, $qty);
        return $stmt->execute();
    }

    // c. Record simulated payment
    public function record_payment($amount, $customer_id, $order_id, $currency, $payment_date) {
        $stmt = $this->pdo->prepare("INSERT INTO payment (amt, customer_id, order_id, currency, payment_date) VALUES (?,?,?,?,?)");
        $stmt->bind_param('diiss', $amount, $customer_id, $order_id, $currency, $payment_date);
        return $stmt->execute();
    }

    // d. Retrieve past orders for a user
    public function get_orders_by_customer($customer_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY order_id DESC");
        $stmt->bind_param('i', $customer_id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }
}
