<?php
session_start();
require_once __DIR__ . '/../../controllers/CartController.php';
require_once __DIR__ . '/../../controllers/ProductController.php';
require_once __DIR__ . '/../../controllers/StoreController.php';
require_once __DIR__ . '/../../controllers/CustomerController.php';
require_once __DIR__ . '/../../env.php';

// Kết nối DB
$conn = new mysqli($hostname, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Lấy thông tin customer và store
$customerId = $_SESSION['CustomerId'] ?? null;
$storeId    = $_POST['storeId'] ?? $_SESSION['CheckoutStoreId'] ?? 0;
if (!$customerId || !$storeId) die('Thiếu thông tin khách hàng hoặc chi nhánh');
$_SESSION['CheckoutStoreId'] = $storeId;

// Controllers
$cartController = new CartController();
$productController = new ProductController();
$storeController = new StoreController();
$customerController = new CustomerController();

// Lấy giỏ hàng
$carts = $cartController->getCartByCustomerId($customerId, $storeId);
if (empty($carts)) die('Giỏ hàng trống.');

// Tính tổng tiền
$totalCOD = 0;
$totalWeight = 0;
$products = [];
foreach ($carts as $cart) {
    $product = $productController->getProductById($cart->ProductId);
    $qty = (int)$cart->Quantity;
    $price = (int)$product->Price;
    $weight = (int)($product->Weight ?? 500);

    $totalCOD += $price * $qty;
    $totalWeight += $weight * $qty;

    $products[] = [
        'name' => $product->Title ?? 'Sản phẩm',
        'code' => (string)$product->Id,
        'quantity' => $qty,
        'price' => $price,
        'weight' => $weight
    ];
}

// Thanh toán ngân hàng
$paymentMethod = $_POST['paymentMethod'] ?? 'cod';
if ($paymentMethod === 'bank') {
    $vnp_TxnRef = time();
    $_SESSION['vnp_OrderInfo'] = ['order_id' => $vnp_TxnRef, 'amount' => $totalCOD];
    header("Location: /oss_trung_nguyen_coffee/app/vnpay_php/vnpay_create_payment.php");
    exit();
}

// GHN config
define('GHN_TOKEN', 'ed799cbf-cfee-11f0-84c8-a649637e7c2d');
define('GHN_SHOP_ID', 6146003); // ShopId 
define('GHN_BASE', 'https://online-gateway.ghn.vn/shiip/public-api/v2');

// Thông tin cửa hàng
$store = $storeController->getStoreById($storeId);
$storeName = $store->StoreName ?? 'Cửa hàng';
$storePhone = preg_replace('/\D/', '', $store->Phone ?? '0123456789');
if (substr($storePhone, 0, 2) === '84') $storePhone = '0' . substr($storePhone, 2);
$storeAddress = $store->Address ?? 'Địa chỉ cửa hàng';
$fromDistrict = $store->District ?? 0;
$fromWard = $store->WardCode ?? '0';

// Thông tin khách hàng
$customer = $customerController->getCustomerById($customerId);
$customerName = $customer->FirstName ?? 'Khách hàng';
$customerPhone = preg_replace('/\D/', '', $customer->Phone ?? '0909123456');
if (substr($customerPhone, 0, 2) === '84') $customerPhone = '0' . substr($customerPhone, 2);
$toAddress = $customer->Address ?? 'Địa chỉ khách hàng';
$toDistrict = $customer->DistrictId ?? 0;
$toWard = $customer->WardCode ?? '0';

// Chế độ demo hoặc thật
$isDemo = true; // true:  demo, false: GHN real
$shipmentData = [];

if (!$isDemo) {
    $payload = [
        "payment_type_id" => 2,
        "required_note" => "KHONGCHOXEMHANG",
        "service_type_id" => 2,
        "note" => "Đơn hàng web",
        "from_name" => $storeName,
        "from_phone" => $storePhone,
        "from_address" => $storeAddress,
        "from_ward_code" => $fromWard,
        "from_district_id" => $fromDistrict,
        "to_name" => $customerName,
        "to_phone" => $customerPhone,
        "to_address" => $toAddress,
        "to_ward_code" => $toWard,
        "to_district_id" => $toDistrict,
        "cod_amount" => $totalCOD,
        "content" => "Thanh toán đơn hàng",
        "weight" => $totalWeight,
        "length" => 20,
        "width" => 20,
        "height" => 20,
        "items" => $products
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, GHN_BASE . "/shipping-order/create");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Token: " . GHN_TOKEN,
        "ShopId: " . GHN_SHOP_ID,
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    if (isset($result['code']) && $result['code'] == 200) {
        $shipmentData = $result['data'] ?? [];
    } else {
        die("<h3>Lỗi GHN:</h3><pre>" . print_r($result, true) . "</pre>");
    }
} else {
    $shipmentData = ['order_code' => 'DEMO' . time(), 'status' => 'ready_to_pick'];
}


$stmtPay = $conn->prepare("INSERT INTO payment (CustomerId, StoreId, Total, Status, CreatedAt) VALUES (?, ?, ?, ?, NOW())");
$statusPay = 'pending';
$stmtPay->bind_param("iids", $customerId, $storeId, $totalCOD, $statusPay);
$stmtPay->execute();
$paymentId = $stmtPay->insert_id;
$stmtPay->close();

$stmtShip = $conn->prepare("INSERT INTO shipment (PaymentId, Carrier, TrackingCode, Status, Latitude, Longitude, UpdatedAt) VALUES (?, ?, ?, ?, ?, ?, NOW())");
$carrier = $isDemo ? "DEMO" : "GHN";
$trackingCode = $shipmentData['order_code'] ?? '';
$statusShip = $shipmentData['status'] ?? 'ready_to_pick';
$lat = null;
$lng = null;
$stmtShip->bind_param("isssdd", $paymentId, $carrier, $trackingCode, $statusShip, $lat, $lng);
$stmtShip->execute();
$stmtShip->close();

// Payment detail
$stmtDetail = $conn->prepare("INSERT INTO paymentdetail (PaymentId, ProductId, Price, Quantity) VALUES (?, ?, ?, ?)");
foreach ($carts as $cart) {
    $product = $productController->getProductById($cart->ProductId);
    $price = (int)$product->Price;
    $quantity = (int)$cart->Quantity;
    $stmtDetail->bind_param("iiid", $paymentId, $cart->ProductId, $price, $quantity);
    $stmtDetail->execute();
}
$stmtDetail->close();

// Xóa giỏ hàng
foreach ($carts as $cart) {
    $cartController->removeFromCart($customerId, $cart->ProductId, $storeId);
}

// Redirect sang view payment
$_SESSION['paymentId'] = $paymentId;
$conn->close();
header("Location: ../payment/index.php");
exit();
