<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['Title']) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        .product-detail {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .product-detail img {
            width: 100%;
            max-width: 400px;
            height: 400px;          /* Cố định chiều cao */
            object-fit: cover;      /* Giữ tỉ lệ, cắt vừa khung */
            border-radius: 15px;
            border: 1px solid #ddd;
        }

        .product-info {
            flex: 1;
            min-width: 250px;
        }

        .product-info h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 15px;
        }

        .product-info p {
            font-size: 16px;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .product-info p.price {
            font-size: 22px;
            font-weight: bold;
            color: #ff6600;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            border-radius: 25px;
            background-color: #ff6600;
            color: #fff;
            text-decoration: none;
            transition: background 0.3s;
        }

        .back-link:hover {
            background-color: #e65500;
        }

        @media(max-width:768px) {
            .product-detail {
                flex-direction: column;
                align-items: center;
            }

            .product-detail img {
                max-width: 90%;
                height: 300px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="product-detail">
        <?php
            $imgPath = "images/Images/SanPham/" . $product['Img'];
            if (!file_exists($imgPath)) {
                $imgPath = "images/Images/SanPham/default.jpg";
            }
        ?>
        <img src="<?= htmlspecialchars($imgPath) ?>" alt="<?= htmlspecialchars($product['Title']) ?>">

        <div class="product-info">
            <h1><?= htmlspecialchars($product['Title']) ?></h1>
            <p class="price"><?= number_format($product['Price']) ?>₫</p>
            <p><strong>Mô tả:</strong> <?= htmlspecialchars($product['Content']) ?></p>
            <p><strong>Đánh giá:</strong> <?= htmlspecialchars($product['Rate']) ?>/5</p>

            <a href="index.php?page=products" class="back-link">← Quay lại danh sách</a>
            <div style="margin-top:20px;">
                <a href="index.php?page=best_seller"
                style="padding:10px 20px; background-color:#ff6600; color:white; border-radius:25px; text-decoration:none;">
                Xem sản phẩm bán chạy
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
