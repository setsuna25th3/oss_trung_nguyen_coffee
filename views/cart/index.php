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
                    <!-- load dữ liệu -->
                    <!-- <li><a href="#">Cà phê sữa đá</a></li>
                    <li><a href="#">Trà đào</a></li>
                    <li><a href="#">Nước đá cam</a></li>
                    <li><a href="#">Cà phê đen</a></li>
                    <li><a href="#">Bánh mì</a></li>
                    <li><a href="#">Bánh flan</a></li> -->
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
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <tr>
                            <!-- load dữ liệu -->
                            <!-- <td><img src="Images/SanPham/sample.jpg" class="product-img" alt="Sản phẩm"></td>
                            <td>Cà phê sữa đá <?= $i ?></td>
                            <td>25.000 VND</td>
                            <td>1</td>
                            <td>25.000 VND</td>
                            <td><button class="btn">Xóa</button></td> -->
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>

            <div class="checkout-box">
                <h4>Tổng tiền:</h4>
                <!-- xử lý tính -->
                <!-- <p>100.000 VND</p> -->
                <button class="btn">Thanh toán</button>
                <!-- xử lý thanh toán -->
            </div>
        </main>
    </div>

    <?php include '../footer.php'; ?>
</body>

</html>