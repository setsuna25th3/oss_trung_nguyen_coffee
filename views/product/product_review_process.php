<?php
// THÊM DÒNG NÀY ĐẦU TIÊN để tránh lỗi Header
ob_start(); 


require_once '../../db_connect.php'; 
require_once '../../models/Product.php';


//PHÉP PHƯƠNG THỨC POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../home/index.php');
    exit();
}

//  KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['customer_id'])) {
    set_flash_message("Vui lòng đăng nhập để đánh giá sản phẩm.", "warning");
    header('Location: ../customer/login.php?returnUrl=' . urlencode($_SERVER['HTTP_REFERER']));
    exit();
}

//  LẤY DỮ LIỆU TỪ FORM
$productId   = (int)($_POST['product_id'] ?? 0);
$rating      = (int)($_POST['rating'] ?? 0);
$comment     = trim($_POST['comment'] ?? '');
$customerId  = $_SESSION['customer_id'];
// LẤY THÊM ID ĐÁNH GIÁ CŨ (đã được thêm vào form)
$reviewId    = (int)($_POST['review_id'] ?? 0); 
$reviewId = ($reviewId > 0) ? $reviewId : null; // Chuyển về null nếu là 0

// Đường dẫn chuyển hướng sau khi xử lý
$redirectUrl = "product_review.php?Id=" . $productId;

//  KIỂM TRA TÍNH HỢP LỆ CỦA DỮ LIỆU (Giữ nguyên)
if ($productId <= 0 || $rating < 1 || $rating > 5 || empty($comment)) {
    set_flash_message("Dữ liệu đánh giá không hợp lệ.", "danger");
    header('Location: ' . $redirectUrl);
    exit();
}


//  THỰC HIỆN THÊM HOẶC CẬP NHẬT ĐÁNH GIÁ
// Sử dụng hàm saveReview mới đã được bổ sung
$result = Product::saveReview($reviewId, $productId, $customerId, $rating, $comment);

if ($result) {
    if ($reviewId !== null) {
        set_flash_message("Đã cập nhật đánh giá của bạn thành công!", "success");
    } else {
        set_flash_message("Cảm ơn bạn đã gửi đánh giá sản phẩm!", "success");
    }
} else {
    set_flash_message("Đã xảy ra lỗi khi lưu đánh giá. Vui lòng thử lại.", "danger");
    error_log("Failed to save review for ProductID: $productId, CustomerID: $customerId");
}

// 7. CHUYỂN HƯỚNG CUỐI CÙNG
header('Location: ' . $redirectUrl);
exit();

ob_end_flush();
?>