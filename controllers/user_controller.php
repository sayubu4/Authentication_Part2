<?php

require_once '../classes/user_class.php';

function register_user_ctr($name, $email, $password, $phone_number, $country, $city, $role)
{
    $customer = new Customer();

    // Check if email already exists
    $existing = $customer->getCustomerByEmail($email);
    if ($existing) {
        return "email_exists";
    }

    $customer_id = $customer->add($name, $email, $password, $phone_number, $country, $city, $role);
    if ($customer_id) {
        return $customer_id;
    }
    return false;
}

function get_customer_by_email_ctr($email)
{
    $customer = new Customer();
    return $customer->getCustomerByEmail($email);
}

function edit_customer_ctr($customer_id, $name, $email, $phone_number, $country, $city)
{
    $customer = new Customer();
    return $customer->editCustomer($customer_id, $name, $email, $phone_number, $country, $city);
}

function delete_customer_ctr($customer_id)
{
    $customer = new Customer();
    return $customer->deleteCustomer($customer_id);
}

function get_all_customers_ctr()
{
    $customer = new Customer();
    return $customer->getAllCustomers();
}

function login_customer_ctr($email, $password)
{
    $customer = new Customer();
    return $customer->loginCustomer($email, $password);
}