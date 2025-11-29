<?php
require_once(__DIR__ . '/../settings/db_class.php');

class Cart extends db_connection {
    private $pdo;

    public function __construct() {
        $this->pdo = $this->db_conn();
    }

    // a. Add a product to the cart (wishlist)
    public function add_to_cart($customer_id, $product_id, $qty = 1) {
        $customer_id = (int)$customer_id;
        $product_id = (int)$product_id;
        $qty = max(1, (int)$qty);

        // f. Check if product already exists in the cart and increment quantity instead of duplicating it
        $stmt = $this->pdo->prepare("SELECT qty FROM cart WHERE c_id = ? AND p_id = ?");
        $stmt->bind_param('ii', $customer_id, $product_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $newQty = (int)$row['qty'] + $qty;
            $ustmt = $this->pdo->prepare("UPDATE cart SET qty = ? WHERE c_id = ? AND p_id = ?");
            $ustmt->bind_param('iii', $newQty, $customer_id, $product_id);
            return $ustmt->execute();
        }

        $istmt = $this->pdo->prepare("INSERT INTO cart (p_id, ip_add, c_id, qty) VALUES (?, '', ?, ?)");
        $istmt->bind_param('iii', $product_id, $customer_id, $qty);
        return $istmt->execute();
    }

    // b. Update the quantity of a product in the cart
    public function update_cart_item($customer_id, $product_id, $qty) {
        $customer_id = (int)$customer_id;
        $product_id = (int)$product_id;
        $qty = max(1, (int)$qty);
        $stmt = $this->pdo->prepare("UPDATE cart SET qty = ? WHERE c_id = ? AND p_id = ?");
        $stmt->bind_param('iii', $qty, $customer_id, $product_id);
        return $stmt->execute();
    }

    // c. Remove a product from the cart completely
    public function remove_from_cart($customer_id, $product_id) {
        $customer_id = (int)$customer_id;
        $product_id = (int)$product_id;
        $stmt = $this->pdo->prepare("DELETE FROM cart WHERE c_id = ? AND p_id = ?");
        $stmt->bind_param('ii', $customer_id, $product_id);
        return $stmt->execute();
    }

    // d. Retrieve all cart items for a given user (with product details)
    public function get_user_cart($customer_id) {
        $customer_id = (int)$customer_id;
        $sql = "SELECT c.p_id AS product_id, c.qty, p.product_title, p.product_price, p.product_image
                FROM cart c
                JOIN products p ON p.product_id = c.p_id
                WHERE c.c_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bind_param('i', $customer_id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    // e. Empty the cart
    public function empty_cart($customer_id) {
        $customer_id = (int)$customer_id;
        $stmt = $this->pdo->prepare("DELETE FROM cart WHERE c_id = ?");
        $stmt->bind_param('i', $customer_id);
        return $stmt->execute();
    }
}
