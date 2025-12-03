<?php
session_start();
require_once '../../env.php';
require_once '../../controllers/CheckoutController.php';

$conn = new mysqli($hostname, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$customerId = $_SESSION['CustomerId'] ?? null;
$storeId = $_POST['storeId'] ?? $_SESSION['CheckoutStoreId'] ?? 0;
$paymentMethod = $_POST['paymentMethod'] ?? 'cod';

$checkout = new CheckoutController($conn);

try {
    $result = $checkout->processOrder($customerId, $storeId, $paymentMethod, true);

    if ($result['vnp'] ?? false) {
        // Redirect sang VNPay
        header("Location: /oss_trung_nguyen_coffee/app/vnpay_php/vnpay_create_payment.php");
    } else {
        $_SESSION['paymentId'] = $result['paymentId'];
        header("Location: ../payment/index.php");
    }
} catch (Exception $e) {
    die('<div class="alert alert-danger text-center">' . $e->getMessage() . '</div>');
}

$conn->close();
exit();
