<?php
    session_start();
    require_once '../../controllers/CartController.php'; 
    require_once '../../controllers/ProductController.php';

    $redirect_url = $_POST['current_url'];

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || (!isset($_POST['product_id']) && !isset($_POST['store_id']))) {
        header('Location: ../home/index.php');
        exit();
    }
    $customerId = (int)($_SESSION['CustomerId'] ?? 0);
    $productId = intval($_POST['product_id'] ?? 0);
    $storeId = intval($_POST['store_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);
    

    if ($customerId <= 0) {
        $_SESSION['error_message'] = "Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.";
        header('Location: ../customer/sign_in.php?redirect=' . urlencode($redirect_url));
        exit();
    } elseif ($productId <= 0 || $storeId <= 0 || $quantity <= 0) {
        $_SESSION['error_message'] = "Dữ liệu sản phẩm không hợp lệ.";
    } else {
        try {
            $productController = new ProductController();
            $product = $productController->getProductById($productId);
            if (!$product) {
                 $_SESSION['error_message'] = "Sản phẩm này không tồn tại.";
            } else {
                $productTitle = $product->Title ?? 'Sản phẩm';
                
                $cartController = new CartController();
                $result = $cartController->addToCart($customerId, $productId, $storeId, $quantity);
                
                if ($result) {
                    $_SESSION['success_message'] = "Đã thêm " . htmlspecialchars($productTitle) . " vào giỏ hàng thành công!";
                } else {
                    $_SESSION['error_message'] = "Lỗi khi thêm sản phẩm vào cơ sở dữ liệu. Vui lòng thử lại.";
                }
            }
        } catch (Exception $e) {
            error_log("Cart Error: " . $e->getMessage());
            $_SESSION['error_message'] = "Lỗi hệ thống trong quá trình thêm vào giỏ hàng.";
        }
    }
    header('Location: ' . $redirect_url);
    exit();
?>