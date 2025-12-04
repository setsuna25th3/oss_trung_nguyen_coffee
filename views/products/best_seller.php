<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sản phẩm bán chạy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }

        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .product-card {
            background-color: #fff;
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .product-card img {
            width: 100%;
            height: 250px; /* Cố định chiều cao */
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .product-card h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }

        .product-card p {
            font-size: 16px;
            color: #ff6600;
            margin-bottom: 10px;
        }

        .product-card .rate {
            color: #f39c12;
            font-weight: bold;
        }

        .product-card a {
            display: inline-block;
            padding: 8px 15px;
            background-color: #ff6600;
            color: #fff;
            text-decoration: none;
            border-radius: 20px;
            transition: background 0.3s;
        }

        .product-card a:hover {
            background-color: #e65500;
        }

        .back-link {
            display: block;
            margin-top: 40px;
            text-align: center;
        }

        .back-link a {
            padding: 10px 20px;
            border-radius: 25px;
            background-color: #ff6600;
            color: #fff;
            text-decoration: none;
            transition: background 0.3s;
        }

        .back-link a:hover {
            background-color: #e65500;
        }

        @media(max-width:768px) {
            .product-card img {
                height: 200px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Top sản phẩm bán chạy</h1>

    <div class="product-list">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $p): ?>
                <?php
                    $imgPath = "images/Images/SanPham/" . $p['Img'];
                    if (!file_exists($imgPath)) {
                        $imgPath = "images/Images/SanPham/default.jpg";
                    }
                ?>
                <div class="product-card">
                    <img src="<?= htmlspecialchars($imgPath) ?>" alt="<?= htmlspecialchars($p['Title']) ?>">
                    <h3><?= htmlspecialchars($p['Title']) ?></h3>
                    <p class="price"><?= number_format($p['Price']) ?>₫</p>
                    <p class="rate">Rate: <?= htmlspecialchars($p['Rate']) ?>/5</p>
                    <a href="index.php?page=product-detail&id=<?= $p['Id'] ?>">Xem chi tiết</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Hiện chưa có sản phẩm bán chạy nào.</p>
        <?php endif; ?>
    </div>

    <div class="back-link">
        <a href="index.php?page=products">← Quay lại danh sách sản phẩm</a>
    </div>
</div>
</body>
</html>
