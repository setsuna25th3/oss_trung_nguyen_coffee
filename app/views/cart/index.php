<?php
    session_start();
    require_once __DIR__ .'/../../controllers/CategoryController.php';
    require_once __DIR__ .'/../../controllers/CartController.php';
    require_once __DIR__ .'/../../controllers/ProductController.php';

    $customerId = isset($_SESSION['CustomerId']) ? $_SESSION['CustomerId'] : 0;
    $categoryId = isset($_GET['category']) ? intval($_GET['category']) : 0;
    $storeId = 0;
    $total = 0;
    $categoryController = new CategoryController();
    $cartController = new CartController();
    $productController = new ProductController();

    $categories = $categoryController->getAllCategories();
    $carts = $cartController->getCartByCustomerId($customerId, $storeId);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - Trung Nguyên Cà Phê</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fff1e0;
            padding-top: 150px;
        }

        .page-header {
            padding: 120px 0 60px;
            background-size: cover;
            background-position: center;
            position: relative;
            background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20230718/pngtree-digital-retailing-illustration-laptop-keyboard-with-shopping-basket-and-e-commerce-image_3903657.jpg');
        }

        .page-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        .page-header h1,
        .page-header .breadcrumb {
            position: relative;
            z-index: 1;
            text-align: center;
            color: #fff;
        }

        .page-header h1 {
            font-size: 48px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }

        .breadcrumb {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
            font-size: 16px;
        }

        .breadcrumb a {
            color: #fff1e0;
            text-decoration: none;
            transition: color 0.3s;
        }

        .breadcrumb a:hover {
            color: #ffb300;
        }

        .breadcrumb .active {
            color: #ffb300;
            font-weight: bold;
        }

        .breadcrumb span.separator {
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            display: flex;
            gap: 30px;
        }

        .sidebar {
            width: 25%;
            min-width: 250px;
        }

        .main-content {
            width: 75%;
        }

        .sidebar-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            padding: 20px;
        }

        .sidebar-section h3 {
            font-size: 20px;
            color: #37474f;
            margin-bottom: 15px;
            border-bottom: 2px solid #ffb300;
            padding-bottom: 10px;
        }

        .sidebar-section ul {
            list-style: none;
        }

        .sidebar-section li {
            margin-bottom: 10px;
        }

        .sidebar-section a {
            color: #555;
            text-decoration: none;
            transition: color 0.3s;
        }

        .sidebar-section a:hover {
            color: #ffb300;
        }

        .sidebar-section .featured-product {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .sidebar-section .featured-product img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
        }

        .sidebar-section .featured-product div {
            flex: 1;
        }

        .sidebar-section .featured-product h4 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .sidebar-section .featured-product p {
            font-size: 14px;
            color: #ffb300;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #ffb300;
            color: white;
            font-weight: 700;
        }

        tr:last-child td {
            border-bottom: none;
        }

        img.product-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            background: #ffb300;
            color: white;
            transition: 0.3s;
        }

        .btn:hover {
            background: #ff9800;
        }

        .checkout-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: right;
            margin-top: 30px;
        }

        .checkout-box h4 {
            margin-bottom: 15px;
            color: #37474f;
        }

        .checkout-box p {
            font-size: 18px;
            color: #ffb300;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <?php include '../header.php'; ?>
    
    <div class="container-fluid page-header">
        <h1>Giỏ hàng</h1>
        <ul class="breadcrumb">
            <li><a href="../home/index.php">Trang chủ</a></li><span class="separator">/</span>
            <li><a href="../contact/index.php">Liên hệ</a></li><span class="separator">/</span>
            <li class="active">Cửa hàng</li>
        </ul>
    </div>

    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-section">
                <h3>Danh mục</h3>
                <ul>
                    <?php if (!empty($categories)): ?>
                        <li><a href="../product/index.php<?php echo $storeId > 0 ? '?store=' . $storeId : ''; ?>">Tất cả danh mục</a></li>
                        <?php foreach ($categories as $cat): ?>
                            <?php
                                $c_Id = is_object($cat) ? $cat->Id : (isset($cat['Id']) ? $cat['Id'] : 0);
                                $c_Title = is_object($cat) ? $cat->Title : (isset($cat['Title']) ? $cat['Title'] : '');
                            ?>
                            <li>
                                <a href="../product/index.php?category=<?php echo $c_Id; ?><?php echo $storeId > 0 ? '&store=' . $storeId : ''; ?>"
                                    <?php echo $categoryId == $c_Id ? 'style="color: #ffb300; font-weight: bold;"' : ''; ?>>
                                    <?php echo htmlspecialchars($c_Title); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><span>Không có danh mục</span></li>
                    <?php endif; ?>
                </ul>
            </div>
        </aside>

        <main class="main-content">
            <table>
                <thead>
                    <tr>
                        <th>Ảnh sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng tiền</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total =  0; ?>
                    <?php foreach ($carts as $cart): ?>
                        <tr>
                            <?php
                                $product = $productController->getProductById($cart->ProductId);
                            ?>
                            <td>
                                <img src="../../img/SanPham/<?php echo htmlspecialchars($product->Img) ?>" class="product-img" alt="Sản phẩm">
                            </td>
                            <td><?php echo htmlspecialchars($product->Title) ?></td>
                            <td><?php echo number_format($product->Price, 0, ',', '.') ?> VNĐ</td>
                            <td><?php echo htmlspecialchars($cart->Quantity) ?></td>
                            <?php 
                                $itemTotal = $product->Price * $cart->Quantity;
                                $total += $itemTotal;
                            ?>
                            <td><?php echo number_format($itemTotal, 0, ',', '.') ?> VNĐ</td>
                            <td><button class="btn">Xóa</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="checkout-box">
                <h4>Tổng tiền:</h4>
                <p><?php echo number_format($total, 0, ',', '.'); ?> VNĐ</p>
                <button class="btn">Thanh toán</button>
                <!-- xử lý thanh toán -->
            </div>
        </main>
    </div>

    <?php include '../footer.php'; ?>
</body>

</html>