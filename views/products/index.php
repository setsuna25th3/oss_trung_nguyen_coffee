<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
    <style>
        /* BODY */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        /* HEADER */
        header {
            width: 100%;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 10px 30px;
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header .logo {
            font-size: 24px;
            font-weight: bold;
            color: #ff6600;
            text-decoration: none;
        }

        header nav a {
            padding: 10px 20px;
            background-color: #ff6600;
            color: #fff;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
            margin-left: 10px;
        }

        header nav a:hover {
            background-color: #e65500;
        }

        /* CONTAINER */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        /* FORM LỌC */
        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .filter-form input[type="text"],
        .filter-form select {
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 25px;
            border: 1px solid #ccc;
            width: 200px;
            transition: all 0.3s;
        }

        .filter-form input[type="text"]:focus,
        .filter-form select:focus {
            border-color: #ff6600;
            outline: none;
        }

        .filter-form button {
            padding: 10px 20px;
            border: none;
            background-color: #ff6600;
            color: white;
            font-weight: bold;
            border-radius: 25px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .filter-form button:hover {
            background-color: #e65500;
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin-bottom: 30px;
        }

        /* DANH SÁCH SẢN PHẨM */
        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .product-card {
            background-color: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            text-align: center;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .product-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        .product-card h3 {
            font-size: 18px;
            color: #333;
            margin: 15px 0 10px 0;
            padding: 0 10px;
        }

        .product-card p {
            color: #ff6600;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .product-card a {
            display: inline-block;
            margin-bottom: 15px;
            padding: 10px 20px;
            border-radius: 25px;
            background-color: #ff6600;
            color: white;
            text-decoration: none;
            transition: background 0.3s;
        }

        .product-card a:hover {
            background-color: #e65500;
        }

        /* BUTTON XEM BÁN CHẠY */
        .best-seller-btn {
            display: block;
            width: fit-content;
            margin: 30px auto;
            padding: 12px 25px;
            background-color: #ff6600;
            color: white;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }

        .best-seller-btn:hover {
            background-color: #e65500;
        }

        /* RESPONSIVE */
        @media(max-width: 768px) {
            .filter-form {
                flex-direction: column;
                align-items: center;
            }

            .filter-form input[type="text"],
            .filter-form select {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<!-- HEADER -->
<header>
    <a href="index.php?page=products" class="logo">MyCoffee</a>
    <nav>
        <a href="index.php?page=profile">Trang cá nhân</a>
    </nav>
</header>

<div class="container">
    <h1>Danh sách sản phẩm</h1>

    <form method="GET" action="" class="filter-form">
        <input type="hidden" name="page" value="products">
        <input type="text" name="search" placeholder="Tìm kiếm..." value="<?= htmlspecialchars($search ?? '') ?>">
        <select name="category">
            <option value="">-- Tất cả loại --</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['Id'] ?>" <?= ($category == $c['Id']) ? "selected" : "" ?>>
                    <?= $c['Title'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Lọc</button>
    </form>

    <hr>

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
                    <p><?= number_format($p['Price']) ?>₫</p>
                    <a href="index.php?page=product-detail&id=<?= $p['Id'] ?>">Xem chi tiết</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center;">Hiện chưa có sản phẩm nào.</p>
        <?php endif; ?>
    </div>
</div>

<a href="index.php?page=best_seller" class="best-seller-btn">Xem sản phẩm bán chạy</a>

</body>
</html>
