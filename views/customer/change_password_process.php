<?php


require_once '../../db_connect.php'; 
require_once '../../models/Customer.php';

// Yêu cầu: PHẢI ĐĂNG NHẬP MỚI ĐƯỢC THỰC HIỆN TIẾN TRÌNH NÀY
if (!isset($_SESSION['customer_id'])) {
    set_flash_message("Vui lòng đăng nhập để thực hiện tác vụ này.", "warning");
    header('Location: login.php');
    exit();
}

// Chỉ xử lý khi form được gửi bằng phương thức POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: change_password.php');
    exit();
}

$customerId = $_SESSION['customer_id'];
$currentPassword = $_POST['CurrentPassword'] ?? '';
$newPassword = $_POST['NewPassword'] ?? '';
$confirmPassword = $_POST['ConfirmPassword'] ?? '';
$errors = [];

// --- 1. Xác thực dữ liệu nhập vào ---
if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
    $errors[] = "Vui lòng nhập đầy đủ các trường.";
}

if ($newPassword !== $confirmPassword) {
    $errors[] = "Mật khẩu mới và Nhập lại mật khẩu không khớp.";
}

// Thêm quy tắc về độ dài/độ phức tạp cho mật khẩu mới
if (strlen($newPassword) < 6) {
    $errors[] = "Mật khẩu mới phải có ít nhất 6 ký tự.";
}

if (!empty($errors)) {
    // SỬ DỤNG HÀM CHUẨN
    set_flash_message(implode('<br>', $errors), "danger");
    header('Location: change_password.php');
    exit();
}

//  Kiểm tra mật khẩu cũ ---
$stmt = $conn->prepare("SELECT Password FROM customer WHERE Id = ?");
$stmt->bind_param("i", $customerId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Trường hợp hiếm: người dùng đã đăng nhập nhưng không tồn tại trong DB
    $errors[] = "Lỗi hệ thống: Không tìm thấy người dùng.";
} else {
    $customerData = $result->fetch_assoc();
    $hashedPassword = $customerData['Password'];
    $stmt->close();
    
    // Kiểm tra mật khẩu cũ
    if (!password_verify($currentPassword, $hashedPassword)) {
        $errors[] = "Mật khẩu cũ không đúng. Vui lòng thử lại.";
    }
}

if (!empty($errors)) {
    // SỬ DỤNG HÀM CHUẨN
    set_flash_message(implode('<br>', $errors), "danger");
    header('Location: change_password.php');
    exit();
}

// --- 3. Cập nhật mật khẩu mới ---
// Băm (hash) mật khẩu mới trước khi lưu vào CSDL
$newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
// Cần sử dụng $conn từ db_connect, nhưng do nó là kết nối MySQLi, ta dùng hàm date()
$updateTime = date('Y-m-d H:i:s');

$updateStmt = $conn->prepare("UPDATE customer SET Password = ?, UpdateAt = ? WHERE Id = ?");
$updateStmt->bind_param("ssi", $newHashedPassword, $updateTime, $customerId);

if ($updateStmt->execute()) {
    // Cập nhật thành công - SỬ DỤNG HÀM CHUẨN
    set_flash_message("Mật khẩu của bạn đã được cập nhật thành công.", "success");
} else {
    // Cập nhật thất bại - SỬ DỤNG HÀM CHUẨN
    set_flash_message("Lỗi hệ thống khi cập nhật mật khẩu: " . $conn->error, "danger");
}
$updateStmt->close();

header('Location: change_password.php');
exit();
?>