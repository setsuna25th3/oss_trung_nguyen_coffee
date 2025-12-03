<?php
require_once __DIR__ . '/CartController.php';
require_once __DIR__ . '/ProductController.php';
require_once __DIR__ . '/StoreController.php';
require_once __DIR__ . '/CustomerController.php';
require_once __DIR__ . '/../env.php';

class CheckoutController
{
    private $cartController;
    private $productController;
    private $storeController;
    private $customerController;
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->cartController = new CartController();
        $this->productController = new ProductController();
        $this->storeController = new StoreController();
        $this->customerController = new CustomerController();
    }

    public function processOrder($customerId, $storeId, $paymentMethod = 'cod', $isDemo = true)
    {
        if (!$customerId || !$storeId) {
            throw new Exception("Thiếu thông tin khách hàng hoặc chi nhánh");
        }

        // Lấy giỏ hàng
        $carts = $this->cartController->getCartByCustomerId($customerId, $storeId);
        if (empty($carts)) throw new Exception("Giỏ hàng trống.");

        // Tính tổng tiền, tổng cân nặng
        $totalCOD = 0;
        $totalWeight = 0;
        $products = [];
        foreach ($carts as $cart) {
            $product = $this->productController->getProductById($cart->ProductId);
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

        // Thanh toán VNPay
        if ($paymentMethod === 'bank') {
            $vnp_TxnRef = time();
            $_SESSION['vnp_OrderInfo'] = ['order_id' => $vnp_TxnRef, 'amount' => $totalCOD];
            return ['vnp' => true]; // báo ra view redirect VNPay
        }

        // Lấy thông tin cửa hàng và khách hàng
        $store = $this->storeController->getStoreById($storeId);
        $customer = $this->customerController->getCustomerById($customerId);

        $storeName = $store->StoreName ?? 'Cửa hàng';
        $storePhone = preg_replace('/\D/', '', $store->Phone ?? '0123456789');
        $storeAddress = $store->Address ?? 'Địa chỉ cửa hàng';
        $fromDistrict = $store->District ?? 0;
        $fromWard = $store->WardCode ?? '0';

        $customerName = $customer->FirstName ?? 'Khách hàng';
        $customerPhone = preg_replace('/\D/', '', $customer->Phone ?? '0909123456');
        $toAddress = $customer->Address ?? 'Địa chỉ khách hàng';
        $toDistrict = $customer->DistrictId ?? 0;
        $toWard = $customer->WardCode ?? '0';

        // GHN demo
        $shipmentData = [];
        if ($isDemo) {
            $shipmentData = ['order_code' => 'DEMO' . time(), 'status' => 'ready_to_pick'];
        }

        // Lưu payment
        $stmtPay = $this->conn->prepare("INSERT INTO payment (CustomerId, StoreId, Total, Status, CreatedAt) VALUES (?, ?, ?, ?, NOW())");
        $statusPay = 'pending';
        $stmtPay->bind_param("iids", $customerId, $storeId, $totalCOD, $statusPay);
        $stmtPay->execute();
        $paymentId = $stmtPay->insert_id;
        $stmtPay->close();

        // Lưu shipment
        $stmtShip = $this->conn->prepare("INSERT INTO shipment (PaymentId, Carrier, TrackingCode, Status, Latitude, Longitude, UpdatedAt) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $carrier = $isDemo ? "DEMO" : "GHN";
        $trackingCode = $shipmentData['order_code'] ?? '';
        $statusShip = $shipmentData['status'] ?? 'ready_to_pick';
        $lat = null;
        $lng = null;
        $stmtShip->bind_param("isssdd", $paymentId, $carrier, $trackingCode, $statusShip, $lat, $lng);
        $stmtShip->execute();
        $stmtShip->close();

        // Payment detail
        $stmtDetail = $this->conn->prepare("INSERT INTO paymentdetail (PaymentId, ProductId, Price, Quantity) VALUES (?, ?, ?, ?)");
        foreach ($carts as $cart) {
            $product = $this->productController->getProductById($cart->ProductId);
            $price = (int)$product->Price;
            $quantity = (int)$cart->Quantity;
            $stmtDetail->bind_param("iiid", $paymentId, $cart->ProductId, $price, $quantity);
            $stmtDetail->execute();
        }
        $stmtDetail->close();

        // Xóa giỏ hàng
        foreach ($carts as $cart) {
            $this->cartController->removeFromCart($customerId, $cart->ProductId, $storeId);
        }

        return ['vnp' => false, 'paymentId' => $paymentId];
    }
}
