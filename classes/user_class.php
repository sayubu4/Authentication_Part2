<?php
require_once '../settings/db_class.php';

class Customer extends db_connection
{
    public $db;

    public function __construct()
    {
        try {
            $this->db = $this->db_conn();
            if (!$this->db) {
                error_log("Database connection failed in Customer constructor");
                throw new Exception("Database connection failed");
            }
            error_log("Database connection successful in Customer constructor");
        } catch (Exception $e) {
            error_log("Database connection error in Customer constructor: " . $e->getMessage());
            throw new Exception("Failed to connect to database: " . $e->getMessage());
        }
    }

    public function add($name, $email, $password, $phone_number, $country, $city, $role)
    {
        // Prevent duplicate email
        $checkStmt = $this->db->prepare("SELECT * FROM customer WHERE customer_email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            return false; // Email exists
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new customer
        $stmt = $this->db->prepare(
            "INSERT INTO customer (customer_name, customer_email, customer_pass, customer_contact, customer_country, customer_city, user_role) 
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssssssi", $name, $email, $hashed_password, $phone_number, $country, $city, $role);

        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function getCustomerByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM customer WHERE customer_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function editCustomer($customer_id, $name, $email, $phone_number, $country, $city)
    {
        $stmt = $this->db->prepare(
            "UPDATE customer SET customer_name = ?, customer_email = ?, customer_contact = ?, customer_country = ?, customer_city = ? WHERE customer_id = ?"
        );
        $stmt->bind_param("sssssi", $name, $email, $phone_number, $country, $city, $customer_id);
        
        return $stmt->execute();
    }

    public function deleteCustomer($customer_id)
    {
        $stmt = $this->db->prepare("DELETE FROM customer WHERE customer_id = ?");
        $stmt->bind_param("i", $customer_id);
        
        return $stmt->execute();
    }

    public function getAllCustomers()
    {
        $stmt = $this->db->prepare("SELECT * FROM customer");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function loginCustomer($email, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM customer WHERE customer_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return "user_not_found";
        }
        
        $customer = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $customer['customer_pass'])) {
            // Return customer data without password
            unset($customer['customer_pass']);
            return $customer;
        } else {
            return "invalid_credentials";
        }
    }
}