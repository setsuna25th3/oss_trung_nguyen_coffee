<?php
require_once("./config.php"); // file config VNPay chứa $vnp_TmnCode, $vnp_HashSecret, $vnp_Url, $vnp_Returnurl

// Nhận dữ liệu từ POST hoặc GET
$vnp_TxnRef = $_POST['vnp_order_id'] ?? $_GET['vnp_order_id'] ?? null;
$vnp_Amount = $_POST['vnp_amount'] ?? $_GET['vnp_amount'] ?? null;

if (!$vnp_TxnRef || !$vnp_Amount) {
    die("Không tìm thấy thông tin đơn hàng.");
}

// Kiểm tra số tiền hợp lệ
if ($vnp_Amount < 5000 || $vnp_Amount > 1000000000) {
    die("Số tiền thanh toán hợp lệ từ 5.000 đến 1.000.000.000 VND");
}

$vnp_Amount = $vnp_Amount * 100; // VND -> "xu"
$vnp_Locale = 'vn';
$vnp_BankCode = '';
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

// Tạo inputData chuẩn VNPay
$inputData = [
    "vnp_Version"    => "2.1.0",
    "vnp_TmnCode"    => $vnp_TmnCode,
    "vnp_Amount"     => $vnp_Amount,
    "vnp_Command"    => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode"   => "VND",
    "vnp_IpAddr"     => $vnp_IpAddr,
    "vnp_Locale"     => $vnp_Locale,
    "vnp_OrderInfo"  => "Thanh toan GD: " . $vnp_TxnRef,
    "vnp_OrderType"  => "other",
    "vnp_ReturnUrl"  => $vnp_Returnurl,
    "vnp_TxnRef"     => $vnp_TxnRef
];

if (!empty($vnp_BankCode)) {
    $inputData['vnp_BankCode'] = $vnp_BankCode;
}

// Sắp xếp key và tạo hash
ksort($inputData);
$hashData = http_build_query($inputData, '', '&', PHP_QUERY_RFC3986);
$vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// Tạo URL VNPay và chuyển hướng
$vnpUrl = $vnp_Url . '?' . $hashData . '&vnp_SecureHash=' . $vnpSecureHash;
header("Location: $vnpUrl");
exit;
