<?php

ob_start(); 

// BẬT HIỂN THỊ LỖI
ini_set('display_errors', 1);
// BẬT HIỂN THỊ LỖI (CHỈ DÙNG TRONG MÔI TRƯỜNG PHÁT TRIỂN)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Session_start() và các hàm hỗ trợ được định nghĩa trong db_connect.php
require_once '../../db_connect.php'; 
require_once '../../models/Customer.php';

// Chỉ xử lý khi form được gửi bằng phương thức POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit();
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$rememberMe = isset($_POST['rememberMe']);
// Đường dẫn chuyển hướng mặc định là ../home/index.php
$returnUrl = $_GET['returnUrl'] ?? '../home/index.php'; 

$errors = [];

// --- 1. Xác thực dữ liệu cơ bản ---
if (empty($email) || empty($password)) {
    $errors[] = "Vui lòng nhập Email và Mật khẩu.";
}

// --- 2. Tìm người dùng trong CSDL ---
$customerData = null;
if (empty($errors)) {
    $stmt = $conn->prepare("SELECT * FROM customer WHERE Email = ? AND IsActive = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $customerData = $result->fetch_assoc();
    }
    $stmt->close();
}

// --- 3. Xác thực Mật khẩu ---
if (!$customerData || !password_verify($password, $customerData['Password'])) {
    $errors[] = "Email hoặc Mật khẩu không chính xác.";
}

// --- 4. Hoàn tất đăng nhập hoặc báo lỗi ---
if (!empty($errors)) {
    // SỬA LỖI: Sử dụng set_flash_message() để chuẩn hóa hiển thị thông báo lỗi
    $errorMessage = $errors[0] ?? "Lỗi đăng nhập không xác định.";
    
    // LƯU LỖI VÀO SESSION DƯỚI DẠNG FLASH MESSAGE (type 'danger' = màu đỏ)
    set_flash_message($errorMessage, "danger"); 
    
    // Giữ lại email đã nhập
    $_SESSION['old_email'] = $email; 
    
    // Chuyển hướng về trang login
    header('Location: login.php?returnUrl=' . urlencode($returnUrl));
    exit();
} else {
    // Đăng nhập thành công

    // Tạo đối tượng Customer và lưu vào Session
    $customer = new Customer($customerData);
    $_SESSION['customer_id'] = $customer->Id;
    $_SESSION['customer_email'] = $customer->Email;
    $_SESSION['customer_role'] = $customer->Role;
    $_SESSION['customer_fullname'] = $customer->LastName . ' ' . $customer->FirstName;
    
    // Xử lý 'Ghi nhớ đăng nhập' (Remember Me)
    if ($rememberMe) {
        // Khắc phục lỗi PHP Version cũ (đã làm trong các bước trước)
        if (function_exists('random_bytes')) {
            $randomKey = bin2hex(random_bytes(32));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $randomKey = bin2hex(openssl_random_pseudo_bytes(32));
        } else {
            $randomKey = md5(uniqid(mt_rand(), true));
        }
        
        $expireTime = time() + (30 * 24 * 60 * 60); // 30 ngày
        
        // Lưu randomKey vào CSDL
        $updateStmt = $conn->prepare("UPDATE customer SET RandomKey = ? WHERE Id = ?");
        $updateStmt->bind_param("si", $randomKey, $customer->Id);
        $updateStmt->execute();
        $updateStmt->close();
        
        // Thiết lập cookie
        setcookie('remember_user', $customer->Id, $expireTime, "/");
        setcookie('remember_key', $randomKey, $expireTime, "/");
    }

    // Hiển thị thông báo thành công
    set_flash_message("Đăng nhập thành công!", "success");
    
    // Chuyển hướng đến URL mục tiêu
    header('Location: ' . $returnUrl);
    exit();
}
// KHÔNG CÓ THẺ ĐÓNG ?>