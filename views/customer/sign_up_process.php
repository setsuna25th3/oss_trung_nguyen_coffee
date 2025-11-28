<?php
require_once '../../db_connect.php'; 
require_once '../../models/Customer.php';

// Kiểm tra xem form đã được gửi đi chưa
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signup.php');
    exit();
}

// Lưu dữ liệu cũ vào session để hiển thị lại trong form nếu có lỗi
$_SESSION['signup_old_data'] = $_POST;

$errors = [];

// Lấy dữ liệu từ form
$lastName   = trim($_POST['LastName'] ?? '');
$firstName  = trim($_POST['FirstName'] ?? '');
$email      = trim($_POST['Email'] ?? '');
$password   = $_POST['Password'] ?? '';
$phone      = trim($_POST['Phone'] ?? '');
// BỔ SUNG TRƯỜNG ĐỊA CHỈ
$address    = trim($_POST['Address'] ?? ''); 

// --- 1. Xác thực dữ liệu cơ bản ---\r\n
if (empty($lastName) || empty($firstName) || empty($email) || empty($password) || empty($phone)) {
    $errors[] = "Vui lòng điền đầy đủ các trường bắt buộc (Họ, Tên, Email, Mật khẩu, SĐT).";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Định dạng Email không hợp lệ.";
}

if (strlen($password) < 6) {
    $errors[] = "Mật khẩu phải có ít nhất 6 ký tự.";
}

// --- 2. Kiểm tra email trùng lặp trong CSDL ---\r\n
if (empty($errors)) {
    $stmt = $conn->prepare("SELECT Id FROM customer WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $errors[] = "Email này đã được đăng ký. Vui lòng sử dụng email khác.";
    }
    $stmt->close();
}

// --- 3. Xử lý ảnh đại diện ---\r\n
$imgName = 'avatar-default.jpg';
if (isset($_FILES['Image']) && $_FILES['Image']['error'] == UPLOAD_ERR_OK) {
    $file = $_FILES['Image'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 2 * 1024 * 1024; // 2MB
    
    if (in_array($file['type'], $allowedTypes) && $file['size'] <= $maxFileSize) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $imgName = uniqid() . '.' . $ext;
        $uploadDir = '../../images/avatar/'; 
        $targetFile = $uploadDir . $imgName;

        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            $errors[] = "Lỗi khi lưu trữ ảnh đại diện.";
            $imgName = 'avatar-default.jpg'; // Đặt lại mặc định nếu lưu thất bại
        }
    } else {
        $errors[] = "Ảnh đại diện không hợp lệ (chỉ chấp nhận JPEG/PNG/GIF, tối đa 2MB).";
    }
}

// --- 4. Lưu vào CSDL hoặc báo lỗi ---\r\n
if (!empty($errors)) {
    $_SESSION['SignUpErrorMessage'] = implode('<br>', $errors);
    header('Location: signup.php');
    exit();
} else {
    // Xóa dữ liệu cũ sau khi xác thực thành công
    unset($_SESSION['signup_old_data']);

    // Mã hóa mật khẩu
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $registeredAt = date('Y-m-d H:i:s');
    $isActive = 1;
    $role = 0;
    
    // SỬA CÂU LỆNH SQL: Thêm cột Address và biến ? tương ứng
    $sql = "INSERT INTO customer (FirstName, LastName, Email, Password, Phone, Address, Img, RegisteredAt, IsActive, Role) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    // SỬA: Thêm $address vào bind_param, định dạng chuỗi (s)
    $stmt->bind_param("ssssssssid", 
        $firstName, $lastName, $email, $hashedPassword, $phone, $address, $imgName, $registeredAt, $isActive, $role);

    if ($stmt->execute()) {
        // Đăng ký thành công, chuyển hướng đến trang đăng nhập với thông báo
        $_SESSION['SignInSuccessMessage'] = "Đăng ký thành công! Vui lòng đăng nhập.";
        header('Location: login.php');
        exit();
    } else {
        // Lỗi CSDL khác
        $_SESSION['SignUpErrorMessage'] = "Lỗi hệ thống khi đăng ký. Vui lòng thử lại.";
        error_log("Customer registration failed: " . $stmt->error);
        header('Location: signup.php');
        exit();
    }
}