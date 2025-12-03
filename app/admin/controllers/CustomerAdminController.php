<?php
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../models/CustomerAdmin.php';

class CustomerAdminController
{
    public function getAllCustomers()
    {
        global $hostname, $username, $dbname, $port;
        $db = new mysqli($hostname, $username, '', $dbname, $port);
        $sql = "SELECT * FROM customer WHERE Role = 0";
        $result = $db->query($sql);
        $customers = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $customer = new CustomerAdmin();
                $customer->Id = $row['Id'];
                $customer->FirstName = $row['FirstName'];
                $customer->LastName = $row['LastName'];
                $customer->Address = $row['Address'];
                $customer->Phone = $row['Phone'];
                $customer->Email = $row['Email'];
                $customer->Img = $row['Img'];
                $customer->RegisteredAt = $row['RegisteredAt'];
                $customer->UpdateAt = $row['UpdateAt'];
                $customer->DateOfBirth = $row['DateOfBirth'];
                $customer->Password = $row['Password'];
                $customer->RandomKey = $row['RandomKey'];
                $customer->IsActive = $row['IsActive'];
                $customer->Role = $row['Role'];
                $customers[] = $customer;
            }
        }
        $db->close();
        return $customers;
    }
    public function getCustomerById($id)
    {
        global $hostname, $username, $dbname, $port;
        $db = new mysqli($hostname, $username, '', $dbname, $port);
        $sql = "SELECT * FROM customer WHERE Role = 0 AND Id = " . (int)$id;
        $result = $db->query($sql);
        $customer = new CustomerAdmin();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $customer->Id = $row['Id'];
            $customer->FirstName = $row['FirstName'];
            $customer->LastName = $row['LastName'];
            $customer->Address = $row['Address'];
            $customer->Phone = $row['Phone'];
            $customer->Email = $row['Email'];
            $customer->Img = $row['Img'];
            $customer->RegisteredAt = $row['RegisteredAt'];
            $customer->UpdateAt = $row['UpdateAt'];
            $customer->DateOfBirth = $row['DateOfBirth'];
            $customer->Password = $row['Password'];
            $customer->RandomKey = $row['RandomKey'];
            $customer->IsActive = $row['IsActive'];
            $customer->Role = $row['Role'];
        }
        $db->close();
        return $customer;
    }
    public function updateCustomer($customerId, $isActive)
    {
        global $hostname, $username, $password, $dbname, $port;
        $db = new mysqli($hostname, $username, $password, $dbname, $port);
        $sql = "UPDATE customer SET IsActive = ? WHERE Id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $isActive, $customerId);
        $result = $stmt->execute();
        return $result && ($stmt->affected_rows > 0);
    }
}
