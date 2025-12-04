<?php
require_once __DIR__ . "/../Authentication/db_connect.php";
require_once __DIR__ . "/../models/Customer.php";

class CustomerModel {
    private $conn;

    public function __construct() {
        global $conn;
        if (!$conn) die("Không thể kết nối database trong CustomerModel");
        $this->conn = $conn;
    }

    // Lấy thông tin khách hàng theo ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM customer WHERE Id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return new Customer($data);
    }

    // Cập nhật thông tin khách hàng
    public function update($id, $data) {
        $stmt = $this->conn->prepare(
            "UPDATE customer SET FirstName=?, LastName=?, Address=?, Phone=?, Email=?, DateOfBirth=? WHERE Id=?"
        );
        $stmt->bind_param(
            "ssssssi",
            $data['FirstName'],
            $data['LastName'],
            $data['Address'],
            $data['Phone'],
            $data['Email'],
            $data['DateOfBirth'],
            $id
        );
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Cập nhật mật khẩu (hash trước)
    public function updatePassword($id, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE customer SET Password=? WHERE Id=?");
        $stmt->bind_param("si", $hash, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Cập nhật ảnh đại diện
    public function updateImg($id, $imgPath) {
        $stmt = $this->conn->prepare("UPDATE customer SET Img=? WHERE Id=?");
        $stmt->bind_param("si", $imgPath, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
?>
