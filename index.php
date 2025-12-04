<?php
require_once "controllers/ProductController.php";
require_once "controllers/CustomerController.php";

$controller = new ProductController();
$customerController = new CustomerController();

$page = $_GET['page'] ?? 'products';

switch ($page) {
    case 'products':
        $controller->index();
        break;

    case 'product-detail':
        $id = $_GET['id'] ?? null;
        $controller->detail($id);
        break;

    case 'best_seller':
        $controller->bestSeller();
        break;

    case 'profile':
        $customerController->profile();
        break;

    case 'profile_update':
        // Gọi function update() trong controller, không cần trùng tên file
        $customerController->update();  
        break;

    default:
        echo "<h1>Trang không tồn tại!</h1>";
        break;
}
?>
