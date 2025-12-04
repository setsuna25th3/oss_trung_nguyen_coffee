<?php
require_once "models/Customer.php";
require_once "Authentication/db_connect.php";

class CustomerController {

    // Hiển thị trang profile
    public function profile() {
        global $conn;

        // Lấy thông tin khách hàng. Ví dụ: mặc định lấy Id = 1
        $customerId = 1;

        $stmt = $conn->prepare("SELECT * FROM customer WHERE Id = ?");
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();
        $stmt->close();

        // Xử lý ảnh đại diện mặc định
        if (empty($customer['Img']) || !file_exists("images/Customer/".$customer['Img'])) {
            $customer['Img'] = "default.jpg";
        }

        include "views/customer/profile.php";
    }

    // Xử lý cập nhật profile
    public function update() {
        global $conn;

        // Lấy Id mặc định
        $customerId = 1;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';

            $stmt = $conn->prepare("UPDATE customer SET FirstName=?, LastName=?, Phone=?, Address=? WHERE Id=?");
            $stmt->bind_param("ssssi", $firstName, $lastName, $phone, $address, $customerId);
            $stmt->execute();
            $stmt->close();

            header("Location: index.php?page=profile"); 
            exit();
        }

        // Lấy thông tin khách hàng hiện tại để hiển thị form
        $stmt = $conn->prepare("SELECT * FROM customer WHERE Id=?");
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();
        $stmt->close();

        // Xử lý ảnh đại diện mặc định
        if (empty($customer['Img']) || !file_exists("images/Customer/".$customer['Img'])) {
            $customer['Img'] = "default.jpg";
        }

        include "views/customer/profile_update.php";
    }
}
?>
