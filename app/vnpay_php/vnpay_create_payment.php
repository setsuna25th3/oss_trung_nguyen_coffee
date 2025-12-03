<?php
session_start();
require_once("./config.php");

$vnp_OrderInfo = $_SESSION['vnp_OrderInfo'] ?? null;
if (!$vnp_OrderInfo) {
    die("Không tìm thấy thông tin đơn hàng.");
}

$vnp_TxnRef = $vnp_OrderInfo['order_id'];
$vnp_Amount = $vnp_OrderInfo['amount'];

if ($vnp_Amount < 5000 || $vnp_Amount > 1000000000) {
    die("Số tiền thanh toán hợp lệ từ 5.000 đến 1.000.000.000 VND");
}

$vnp_Amount = $vnp_Amount * 100; // VND -> "xu"
$vnp_Locale = 'vn';
$vnp_BankCode = '';
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

$inputData = [
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => $vnp_Amount,
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => $vnp_IpAddr,
    "vnp_Locale" => $vnp_Locale,
    "vnp_OrderInfo" => "Thanh toan GD:" . $vnp_TxnRef,
    "vnp_OrderType" => "other",
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef" => $vnp_TxnRef
];

if (!empty($vnp_BankCode)) {
    $inputData['vnp_BankCode'] = $vnp_BankCode;
}

ksort($inputData);
$hashData = '';
foreach ($inputData as $key => $value) {
    if ($hashData != '') $hashData .= '&';
    $hashData .= $key . '=' . $value;
}

$vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// Tạo URL chuẩn
$vnpUrl = $vnp_Url . '?' . $hashData . '&vnp_SecureHash=' . $vnpSecureHash;

// Debug (in ra để so sánh)
// echo "HashData: $hashData<br>";
// echo "SecureHash: $vnpSecureHash<br>";
// echo "VNPAY URL: $vnpUrl<br>";

// Chuyển hướng
header("Location: $vnpUrl");
exit;


// Không chuyển hướng ngay, để xem giá trị
// Khi chắc chắn đúng mới dùng header chuyển hướng
// $vnpUrl = $vnp_Url . '?' . http_build_query($inputData) . '&vnp_SecureHash=' . $vnpSecureHash;
// header("Location: $vnpUrl");
// exit;
