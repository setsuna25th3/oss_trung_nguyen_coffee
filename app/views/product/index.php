<?php
<<<<<<< HEAD
session_start();
$selectedStore = $_SESSION['SelectedStore'] ?? 0;
=======
// session_start();
require_once '../header.php';
>>>>>>> b9bcb9dc38cb50174a7b7d38d26ab720d6931076

require_once '../../controllers/ProductController.php';
require_once '../../controllers/CategoryController.php';
require_once '../../controllers/StoreController.php';
<<<<<<< HEAD
require_once '../../controllers/CartController.php';

$successMessage = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);

$errorMessage = $_SESSION['error_message'] ?? '';
unset($_SESSION['error_message']);

$categoryController = new CategoryController();
$categories = $categoryController->getAllCategories();

$storeController = new StoreController();
$stores = $storeController->getAllStores();
=======

$categoryId = isset($_GET['category']) ? intval($_GET['category']) : 0;
$storeId = isset($_GET['store']) ? intval($_GET['store']) : 0;
$limits = 5;

$categoryController = new CategoryController();
$categories = $categoryController->getAllCategories();

$storeController = new StoreController();
$stores = $storeController->getAllStores();

$productController = new ProductController();
$products = $productController->getAllProducts($storeId, $categoryId);
$featuredProducts = $productController->getFeaturedProducts($storeId, $limits);
$latestProducts = $productController->getLatestProducts($storeId, $limits);

$customerId = $_SESSION['CustomerId'] ?? 0;
>>>>>>> b9bcb9dc38cb50174a7b7d38d26ab720d6931076

$productController = new ProductController();

$customerId = $_SESSION['CustomerId'] ?? 0;
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cửa hàng - Trung Nguyên Cà Phê</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff1e0;
            color: #333;
            padding-top: 150px;
        }

        .page-header {
            padding: 120px 0 60px;
            background-size: cover;
            background-position: center;
            position: relative;
            background-image: url('https://thesaigontimes.vn/wp-content/uploads/2022/07/Dungdequatre.jpeg.jpg');
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
            color: white;
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
            font-size: 16px;
            flex-wrap: wrap;
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

        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 40px;
            gap: 15px;
            padding: 0 20px;
        }

        .filter-bar .search-container {
            position: relative;
            flex: 1;
            min-width: 300px;
        }

        .filter-bar input[type="search"] {
            padding: 12px 40px 12px 15px;
            font-size: 16px;
            border-radius: 25px;
            border: 1px solid #ddd;
            width: 60%;
            background: white;
            transition: border 0.3s;
        }

        .filter-bar input[type="search"]:focus {
            border-color: #ffb300;
            outline: none;
        }

        .filter-bar .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #37474f;
        }

        .filter-bar select {
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 25px;
            border: 1px solid #ddd;
            background: white;
            min-width: 200px;
            cursor: pointer;
            transition: border 0.3s;
        }

        .filter-bar select:focus {
            border-color: #ffb300;
            outline: none;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, 260px);
            gap: 30px;
            justify-content: center;
        }

        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .card:hover img {
            transform: scale(1.05);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 10px;
            color: #37474f;
        }

        .card-text {
            font-size: 14px;
            color: #666;
            height: 4.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            line-clamp: 3;
<<<<<<< HEAD
=======
            /* Standard property for compatibility */
>>>>>>> b9bcb9dc38cb50174a7b7d38d26ab720d6931076
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .card .price {
            font-weight: 700;
            font-size: 18px;
            color: #ffb300;
            margin-bottom: 15px;
            display: block;
        }

        .card .btn {
            font-size: 14px;
            border-radius: 25px;
            padding: 10px 20px;
            background-color: #ffb300;
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
            width: 100%;
        }

        .card .btn:hover {
            background-color: #ff9800;
            transform: translateY(-2px);
        }

        .pagination {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 50px;
            margin-bottom: 50px;
            list-style: none;
        }

        .pagination li a {
            text-decoration: none;
            color: #37474f;
            padding: 10px 15px;
            border-radius: 50%;
            border: 1px solid #ddd;
            transition: background 0.3s, color 0.3s;
            font-weight: 600;
        }

        .pagination li a.active {
            background: #ffb300;
            color: white;
            border-color: #ffb300;
        }

        .pagination li a:hover {
            background: #ffb300;
            color: white;
            border-color: #ffb300;
        }

        @media(max-width:992px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }

            .main-content {
                width: 100%;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            }
        }

        @media(max-width:576px) {
            .filter-bar {
                flex-direction: column;
                padding: 0 15px;
            }

            .filter-bar .search-container {
                min-width: auto;
            }

            .card img {
                height: 180px;
            }

            .page-header h1 {
                font-size: 36px;
            }

            .breadcrumb {
                font-size: 14px;
            }
        }

        .branch-select-box {
            background: white;
            padding: 20px;
            margin: 30px auto;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 60%;
            text-align: center;
        }

        .branch-select-box label {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
        }

        .branch-select {
            width: 80%;
            padding: 12px 15px;
            border-radius: 25px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .custom-dropdown {
            position: relative;
            width: 80%;
            margin: 0 auto;
        }

        #branchInput {
            width: 100%;
            padding: 12px 15px;
            border-radius: 25px;
            border: 1px solid #ccc;
            cursor: pointer;
        }

        .dropdown-options {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            max-height: 250px;
            overflow-y: auto;
            background: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            z-index: 10;
        }

        .dropdown-options input {
            width: 95%;
            margin: 5px auto;
            display: block;
            padding: 8px;
            border-radius: 15px;
            border: 1px solid #ccc;
        }

        .option {
            padding: 8px 12px;
            cursor: pointer;
        }

        .option:hover {
            background-color: #ffebc0;
        }

        .custom-dropdown {
            position: relative;
            width: 80%;
            margin: 15px auto 0;
        }

        #branchInput {
            width: 100%;
            padding: 14px 20px;
            border-radius: 25px;
            border: 2px solid #ccc;
            background: white;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
            text-align: left;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23666' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
        }

        #branchInput.active {
            border-color: #ffb300;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
            box-shadow: 0 4px 15px rgba(255, 179, 0, 0.2);
        }

        .dropdown-options {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: white;
            border: 2px solid #ffb300;
            border-top: none;
            border-radius: 0 0 25px 25px;
            max-height: 300px;
            overflow: hidden;
            z-index: 1000;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .dropdown-options.active {
            display: block;
        }

        .dropdown-options input#searchBranch {
            width: 90%;
            margin: 10px auto;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            display: block;
            font-size: 15px;
        }

        #branchOptions .option {
            padding: 12px 20px;
            cursor: pointer;
            transition: background 0.2s;
        }

        #branchOptions .option:hover,
        #branchOptions .option.highlight {
            background-color: #fff8e1 !important;
            color: #e67e22;
            font-weight: 600;
        }

        #branchOptions .option.selected {
            background-color: #ffb300;
            color: white;
        }

        .branch-select-box {
            background: white;
            padding: 20px;
            margin: 30px auto;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 60%;
            text-align: center;
        }

        .custom-dropdown {
            position: relative;
            width: 80%;
            margin: 15px auto 0;
        }

        #branchInput {
            width: 100%;
            padding: 14px 20px;
            border-radius: 25px;
            border: 2px solid #ccc;
            background: white;
            cursor: pointer;
            font-size: 16px;
            text-align: left;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23666' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
        }

        #branchOptions {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: white;
            border: 2px solid #ffb300;
            border-top: none;
            border-radius: 0 0 25px 25px;
            max-height: 300px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        }

        #branchOptions .option {
            padding: 12px 20px;
            cursor: pointer;
            transition: background 0.2s;
        }

        #branchOptions .option:hover {
            background-color: #fff8e1;
            color: #e67e22;
            font-weight: 600;
        }

        #branchOptions .option.selected {
            background-color: #ffb300;
            color: white;
        }

        .top-toast {
            background-color: #ffb300;
            /* màu vàng như giỏ hàng */
            color: #fff;
            padding: 15px 25px;
            border-radius: 0 0 10px 10px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transform: translateY(-100%);
            transition: transform 0.5s ease, opacity 0.5s ease;
            opacity: 0.95;
            text-align: center;
            max-width: 400px;
            margin: 0 auto;
        }

        .top-toast.show {
            transform: translateY(0);
        }
    </style>
</head>

<body>
<<<<<<< HEAD
    <div id="topToastContainer" style="position: fixed; top: 0; left: 50%; transform: translateX(-50%); z-index: 9999; width: auto;"></div>

    <?php include '../header.php'; ?>
=======
>>>>>>> b9bcb9dc38cb50174a7b7d38d26ab720d6931076

    <div class="container-fluid page-header">
        <h1 class="display-6 fw-bold font-monospace">Trung Nguyên Cà Phê</h1>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="../home/index.php">Trang chủ</a></li>
            <span class="separator">/</span>
            <li class="breadcrumb-item"><a href="../cart/index.php">Giỏ hàng</a></li>
            <span class="separator">/</span>
            <li class="breadcrumb-item active">Cửa hàng</li>
        </ul>
    </div>

    <div class="branch-select-box">
<<<<<<< HEAD
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php endif; ?>
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger"><?= $errorMessage ?></div>
        <?php endif; ?>

        <label>Chọn chi nhánh:</label>
        <div class="custom-dropdown">
            <input type="text" id="branchInput" placeholder="Chọn chi nhánh...">
            <div id="branchOptions">
                <?php foreach ($stores as $store):
                    $s_Id = is_object($store) ? $store->Id : $store['Id'];
                    $s_Name = is_object($store) ? $store->StoreName : $store['StoreName'];
                    $s_Address = is_object($store) ? $store->Address : $store['Address'];
                ?>
                    <div class="option" data-id="<?= $s_Id ?>"><?= htmlspecialchars($s_Name . ' - ' . $s_Address) ?></div>
                <?php endforeach; ?>
            </div>
        </div>
=======
        <label for="branchSelect">Chọn chi nhánh:</label>
        <form method="get" action="" id="branchForm">
            <select id="branchSelect" name="store" class="branch-select" onchange="document.getElementById('branchForm').submit()">
                <option value="">-- Chọn chi nhánh --</option>
                <?php if (!empty($stores)): ?>
                    <?php foreach ($stores as $store): ?>
                        <?php
                        $s_Id = is_object($store) ? $store->Id : (isset($store['Id']) ? $store['Id'] : 0);
                        $s_Name = is_object($store) ? $store->StoreName : (isset($store['StoreName']) ? $store['StoreName'] : '');
                        $s_Address = is_object($store) ? (isset($store->Address) ? $store->Address : '') : (isset($store['Address']) ? $store['Address'] : '');
                        ?>
                        <option value="<?php echo $s_Id; ?>" <?php echo $storeId == $s_Id ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($s_Name) . (!empty($s_Address) ? ' - ' . htmlspecialchars($s_Address) : ''); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <input type="hidden" name="category" value="<?php echo $categoryId; ?>">
        </form>
>>>>>>> b9bcb9dc38cb50174a7b7d38d26ab720d6931076
    </div>

    <div class="container" style="display:none;" id="productsContainer">
        <aside class="sidebar">
            <div class="sidebar-section">
                <h3>Danh mục</h3>
<<<<<<< HEAD
                <ul id="categoryList">
                    <li><a href="#" data-id="0">Tất cả danh mục</a></li>
                    <?php foreach ($categories as $cat):
                        $c_Id = is_object($cat) ? $cat->Id : $cat['Id'];
                        $c_Title = is_object($cat) ? $cat->Title : $cat['Title'];
                    ?>
                        <li><a href="#" data-id="<?= $c_Id ?>"><?= htmlspecialchars($c_Title) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
=======
                <ul>
                    <?php if (!empty($categories)): ?>
                        <li><a href="index.php<?php echo $storeId > 0 ? '?store=' . $storeId : ''; ?>">Tất cả danh mục</a></li>
                        <?php foreach ($categories as $cat): ?>
                            <?php
                            $c_Id = is_object($cat) ? $cat->Id : (isset($cat['Id']) ? $cat['Id'] : 0);
                            $c_Title = is_object($cat) ? $cat->Title : (isset($cat['Title']) ? $cat['Title'] : '');
                            ?>
                            <li>
                                <a href="index.php?category=<?php echo $c_Id; ?><?php echo $storeId > 0 ? '&store=' . $storeId : ''; ?>"
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

            <div class="sidebar-section">
                <h3>Sản phẩm nổi bật</h3>
                <?php if (!empty($featuredProducts)): ?>
                    <?php foreach ($featuredProducts as $product): ?>
                        <?php
                        $p_Id = is_object($product) ? $product->Id : (isset($product['Id']) ? $product['Id'] : 0);
                        $p_Title = is_object($product) ? $product->Title : (isset($product['Title']) ? $product['Title'] : '');
                        $p_Price = is_object($product) ? $product->Price : (isset($product['Price']) ? $product['Price'] : 0);
                        $p_Img = is_object($product) ? $product->Img : (isset($product['Img']) ? $product['Img'] : '');
                        ?>
                        <div class="featured-product">
                            <a href="detail.php">
                                <?php if (!empty($p_Img)): ?>
                                    <img src="../../img/SanPham/<?php echo htmlspecialchars($p_Img); ?>" alt="<?php echo htmlspecialchars($p_Title); ?>">
                                <?php else: ?>
                                    <img src="../../img/SanPham/sample.jpg" alt="Sản phẩm nổi bật">
                                <?php endif; ?>
                                <?php
                                $_SESSION['currentProductId'] = $p_Id;
                                $_SESSION['currertStoreId'] = $storeId;
                                ?>
                            </a>
                            <div>
                                <h4><?php echo htmlspecialchars($p_Title); ?></h4>
                                <p><?php echo number_format($p_Price, 0, ',', '.'); ?> VND</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #999;">Không có sản phẩm nổi bật</p>
                <?php endif; ?>
            </div>

            <div class="sidebar-section">
                <h3>Sản phẩm mới</h3>
                <?php if (!empty($latestProducts)): ?>
                    <?php foreach ($latestProducts as $product): ?>
                        <?php
                        $p_Id = is_object($product) ? $product->Id : (isset($product['Id']) ? $product['Id'] : 0);
                        $p_Title = is_object($product) ? $product->Title : (isset($product['Title']) ? $product['Title'] : '');
                        $p_Price = is_object($product) ? $product->Price : (isset($product['Price']) ? $product['Price'] : 0);
                        $p_Img = is_object($product) ? $product->Img : (isset($product['Img']) ? $product['Img'] : '');
                        ?>
                        <div class="featured-product">
                            <a href="detail.php">
                                <?php if (!empty($p_Img)): ?>
                                    <img src="../../img/SanPham/<?php echo htmlspecialchars($p_Img); ?>" alt="<?php echo htmlspecialchars($p_Title); ?>">
                                <?php else: ?>
                                    <img src="../../img/SanPham/sample.jpg" alt="Sản phẩm">
                                <?php endif; ?>
                                <?php
                                $_SESSION['currentProductId'] = $p_Id;
                                ?>
                            </a>
                            <div>
                                <h4><?php echo htmlspecialchars($p_Title); ?></h4>
                                <p><?php echo number_format($p_Price, 0, ',', '.'); ?> VND</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #999;">Không có sản phẩm nổi bật</p>
                <?php endif; ?>
            </div>

            <div class="sidebar-section">
                <h3>Sản phẩm bán chạy</h3>
                <!-- Lọc dựa trên số lượng bán -->

                <!-- <div class="featured-product">
                    <img src="Images/SanPham/sample.jpg" alt="Sản phẩm bán chạy">
                    <div>
                        <h4>Latte</h4>
                        <p>30.000 VND</p>
                    </div>
                </div> -->
                <!-- Thêm nhiều hơn nếu cần -->
            </div>
>>>>>>> b9bcb9dc38cb50174a7b7d38d26ab720d6931076
        </aside>

        <main class="main-content">
            <div id="cartMessage" style="display:none; padding:10px; margin-bottom:15px; border-radius:5px;"></div>

            <div class="filter-bar">
                <input type="search" id="searchInput" placeholder="Tìm kiếm sản phẩm...">
                <select id="sortSelect">
                    <option value="">Tất cả</option>
                    <option value="price_desc">Giá cao đến thấp</option>
                    <option value="price_asc">Giá thấp đến cao</option>
                    <option value="rate_desc">Đánh giá cao nhất</option>
                    <option value="latest_desc">Sản phẩm mới nhất</option>
                </select>
            </div>
<<<<<<< HEAD
            <div class="products-grid" id="productsGrid">
                <!-- Sản phẩm sẽ load bằng JS -->
=======

            <div class="products-grid">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <?php
                        $p_Id = is_object($product) ? $product->Id : (isset($product['Id']) ? $product['Id'] : 0);
                        $p_Title = is_object($product) ? $product->Title : (isset($product['Title']) ? $product['Title'] : '');
                        $p_Content = is_object($product) ? $product->Content : (isset($product['Content']) ? $product['Content'] : '');
                        $p_Price = is_object($product) ? $product->Price : (isset($product['Price']) ? $product['Price'] : 0);
                        $p_Rate = is_object($product) ? $product->Rate : (isset($product['Rate']) ? $product['Rate'] : 0);
                        $p_Img = is_object($product) ? $product->Img : (isset($product['Img']) ? $product['Img'] : '');
                        $p_CategoryTitle = is_object($product) ? (isset($product->CategoryTitle) ? $product->CategoryTitle : '') : (isset($product['CategoryTitle']) ? $product['CategoryTitle'] : '');
                        ?>
                        <div class="card">
                            <a href="detail.php">
                                <?php if (!empty($p_Img)): ?>
                                    <img src="../../img/SanPham/<?php echo htmlspecialchars($p_Img); ?>" alt="<?php echo htmlspecialchars($p_Title); ?>">
                                <?php else: ?>
                                    <img src="../../img/SanPham/sample.jpg" alt="Sản phẩm">
                                <?php endif; ?>
                                <?php
                                $_SESSION['currentProductId'] = $p_Id;
                                ?>
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($p_Title); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars(substr($p_Content, 0, 100)); ?></p>
                                <span class="price"><?php echo number_format($p_Price, 0, ',', '.'); ?> VND</span>
                                <button class="btn"><i class="fa fa-shopping-bag me-2"></i>Thêm vào giỏ</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1/-1; text-align: center; padding: 40px;">
                        <p>Không tìm thấy sản phẩm nào</p>
                    </div>
                <?php endif; ?>
>>>>>>> b9bcb9dc38cb50174a7b7d38d26ab720d6931076
            </div>
        </main>
    </div>

    <?php include '../footer.php'; ?>

    <script>
        let selectedStore = 0;
        let selectedCategory = 0;
        let searchString = '';
        let sortValue = '';
        window.addEventListener('DOMContentLoaded', () => {
            const preSelectedStore = <?= $selectedStore ?>;
            if (preSelectedStore != 0) {
                const opt = Array.from(branchOptions.children).find(o => o.dataset.id == preSelectedStore);
                if (opt) {
                    branchInput.value = opt.textContent;
                    selectedStore = preSelectedStore;
                    document.getElementById('productsContainer').style.display = 'flex';
                    loadProducts();
                }
            }
        });

        // Dropdown chi nhánh
        const branchInput = document.getElementById('branchInput');
        const branchOptions = document.getElementById('branchOptions');
        branchInput.addEventListener('click', () => {
            branchOptions.style.display = 'block';
        });
        branchInput.addEventListener('input', () => {
            const filter = branchInput.value.toLowerCase();
            Array.from(branchOptions.children).forEach(opt => {
                opt.style.display = opt.textContent.toLowerCase().includes(filter) ? 'block' : 'none';
            });
        });
        Array.from(branchOptions.children).forEach(opt => {
            opt.addEventListener('click', () => {
                selectedStore = opt.dataset.id;
                branchInput.value = opt.textContent;
                branchOptions.style.display = 'none';
                document.getElementById('productsContainer').style.display = 'flex';
                loadProducts();

                // Lưu chi nhánh vào session
                fetch('set_store_session.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `store=${selectedStore}`
                });
            });
        });


        // Category filter
        document.querySelectorAll('#categoryList a').forEach(cat => {
            cat.addEventListener('click', e => {
                e.preventDefault();
                selectedCategory = cat.dataset.id;
                loadProducts();
            });
        });

        // Search input
        document.getElementById('searchInput').addEventListener('input', e => {
            searchString = e.target.value;
            loadProducts();
        });

        // Sort select
        document.getElementById('sortSelect').addEventListener('change', e => {
            sortValue = e.target.value;
            loadProducts();
        });

        function loadProducts() {
            if (selectedStore == 0) return;

            const xhr = new XMLHttpRequest();
            const params = `store=${selectedStore}&category=${selectedCategory}&searchString=${encodeURIComponent(searchString)}&sort=${sortValue}`;
            xhr.open('GET', `get_products.php?${params}`, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('productsGrid').innerHTML = this.responseText;
                } else {
                    document.getElementById('productsGrid').innerHTML = '<p>Không thể load sản phẩm.</p>';
                }
            };
            xhr.send();
        }
        document.getElementById('productsGrid').addEventListener('click', function(e) {
            if (e.target.classList.contains('add_to_cart')) {
                const btn = e.target;
                const productId = btn.dataset.id;
                const storeId = selectedStore;
                if (storeId == 0) {
                    alert("Vui lòng chọn chi nhánh");
                    return;
                }

                fetch('add_to_cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `action=add_to_cart&product_id=${productId}&store_id=${storeId}&quantity=1`
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            showTopToast(`Bạn đã thêm ${res.productName} vào giỏ!`);

                            const cartCountElem = document.getElementById('cartCount');
                            let currentCount = parseInt(cartCountElem.textContent) || 0;
                            cartCountElem.textContent = currentCount + 1;
                        } else {
                            showTopToast(res.message, 3000);
                        }
                    })
                    .catch(err => {
                        showTopToast('Lỗi server', 3000);
                        console.error(err);
                    });
            }
        });




        function showTopToast(message, duration = 3000) {
            const container = document.getElementById('topToastContainer');
            const toast = document.createElement('div');
            toast.className = 'top-toast';
            toast.textContent = message;
            container.appendChild(toast);

            // Slide xuống
            setTimeout(() => toast.classList.add('show'), 100);

            // Ẩn sau duration
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => container.removeChild(toast), 500);
            }, duration);
        }
    </script>

</body>

</html>