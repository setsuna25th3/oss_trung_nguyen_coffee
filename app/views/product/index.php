<?php
    session_start();
    require_once '../../controllers/ProductController.php';
    require_once '../../controllers/CategoryController.php';
    require_once '../../controllers/StoreController.php';

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

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cửa hàng - Trung Nguyên Cà Phê</title>
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

        /* PAGE HEADER */
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
            /* Tăng độ tối để chữ nổi bật hơn */
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
            gap: 5px;
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

        /* MAIN CONTENT LAYOUT */
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

        /* SIDEBAR STYLES */
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

        /* SEARCH + SORT */
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
            width: 100%;
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
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
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
            line-clamp: 3; /* Standard property for compatibility */
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
    </style>
</head>

<body>
    <?php require_once '../header.php'; ?>

    <div class="container-fluid page-header">
        <h1 class="display-6 fw-bold font-monospace">Trung Nguyên Cà Phê</h1>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="../home/index.php">Trang chủ</a></li>
            <span class="separator">/</span>
            <li class="breadcrumb-item"><a href="../cart/index.php">Giỏ hàng</a></li>
            <span class="separator">/</span>
            <li class="breadcrumb-item"><a href="../contact/index.php">Liên hệ</a></li>
            <span class="separator">/</span>
            <li class="breadcrumb-item active">Cửa hàng</li>
        </ul>
    </div>

    <div class="branch-select-box">
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
    </div>

    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-section">
                <h3>Danh mục</h3>
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
        </aside>

        <main class="main-content">
            <div class="filter-bar">
                <form method="get" action="#" class="search-container">
                    <input type="search" name="searchTerm" placeholder="Tìm kiếm sản phẩm...">
                    <i class="fas fa-search search-icon"></i>
                </form>
                <form method="get" action="#">
                    <select onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <option value="price_desc">Giá cao đến thấp</option>
                        <option value="price_asc">Giá thấp đến cao</option>
                        <option value="rate_desc">Đánh giá cao nhất</option>
                    </select>
                </form>
            </div>

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
            </div>

            <ul class="pagination">
                <li><a href="#">«</a></li>
                <li><a class="active" href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">»</a></li>
            </ul>
        </main>
    </div>

    <?php require_once '../footer.php'; ?>
</body>

</html>