<?php
// Giả sử $product là mảng chứa thông tin sản phẩm
$product = [
    'Id' => 1,
    'Title' => 'Cà phê Trung Nguyên',
    'CategoryTitle' => 'Cà phê rang xay',
    'Price' => 120000,
    'Img' => 'ca-phe-trung-nguyen.jpg',
    'Rate' => 4.5, // Dùng Rate này để hiển thị tóm tắt
    'Content' => 'Cà phê Trung Nguyên chất lượng cao, thơm ngon, phù hợp mọi người.'
];

// Featured products (tạm thời)
$featuredProducts = [
    ['Title' => 'Cà phê hòa tan', 'Img' => 'hoa-tan.jpg', 'Price' => 90000],
    ['Title' => 'Cà phê chồn', 'Img' => 'ca-phe-chon.jpg', 'Price' => 500000],
];

// Related products (tạm thời)
$relatedProducts = [
    ['Title' => 'Cà phê Arabica', 'Img' => 'arabica.jpg', 'Price' => 150000],
    ['Title' => 'Cà phê Robusta', 'Img' => 'robusta.jpg', 'Price' => 100000],
];
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm - Trung Nguyên Cà Phê</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f5f2; /* Nền kem nhẹ nhàng */
            color: #333;
            padding-top: 150px; /* Khoảng cách với header fixed */
        }
        .product-detail-card {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            padding: 30px;
        }
        .product-image {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .price {
            font-size: 2rem;
            font-weight: 700;
            color: #A0522D; /* Màu nâu đất */
        }
        .rating-stars i {
            color: #ffc107;
            font-size: 1.2rem;
        }
        .btn-add-cart {
            background-color: #A0522D;
            border-color: #A0522D;
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }
        .btn-add-cart:hover {
            background-color: #8D4B26;
        }
        .review-link {
            border: 1px solid #A0522D;
            color: #A0522D;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .review-link:hover {
            background-color: #A0522D;
            color: white;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <?php include '../header.php'; ?>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-9">
                <div class="product-detail-card mb-5">
                    <div class="row g-4">
                        <!-- Cột Ảnh -->
                        <div class="col-md-5">
                            <img src="../images/sanpham/<?php echo $product['Img']; ?>" class="img-fluid product-image" alt="<?php echo $product['Title']; ?>">
                        </div>

                        <!-- Cột Thông tin -->
                        <div class="col-md-7">
                            <h2 class="fw-bold text-uppercase mb-3" style="color: #4e342e;"><?php echo $product['Title']; ?></h2>
                            <p class="text-muted mb-2">Danh mục: <span class="fw-semibold"><?php echo $product['CategoryTitle']; ?></span></p>
                            
                            <div class="d-flex align-items-center mb-3">
                                <div class="rating-stars me-2">
                                    <!-- Hiển thị Rating -->
                                    <?php 
                                    $rate = $product['Rate'];
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= floor($rate)) {
                                            echo '<i class="fas fa-star"></i>';
                                        } elseif ($i - 1 < $rate && $i > $rate) {
                                            echo '<i class="fas fa-star-half-alt"></i>';
                                        } else {
                                            echo '<i class="far fa-star" style="color:#ddd;"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                                <span class="fw-semibold" style="color: #A0522D;">(<?php echo number_format($product['Rate'], 1); ?>/5)</span>
                                
                                <!-- Nút Xem/Gửi Đánh giá MỚI -->
                                <a href="product_review.php?Id=<?php echo $product['Id']; ?>" class="ms-3 text-decoration-underline fw-semibold" style="color: #A0522D;">
                                    Xem tất cả đánh giá
                                </a>
                            </div>

                            <p class="price mb-4"><?php echo number_format($product['Price'], 0, ",", "."); ?> VND</p>
                            
                            <!-- Mô tả ngắn gọn -->
                            <h5 class="fw-bold" style="color: #5D4037;">Mô tả</h5>
                            <p class="mb-4 text-justify"><?php echo $product['Content']; ?></p>
                            
                            <!-- Actions -->
                            <div class="d-flex align-items-center mt-4">
                                <input type="number" class="form-control me-3" style="width: 100px; padding: 10px;" value="1" min="1">
                                <button class="btn btn-add-cart me-3"><i class="fas fa-shopping-bag me-2"></i>Thêm vào giỏ</button>
                                <a href="product_review.php?Id=<?php echo $product['Id']; ?>" class="btn review-link">Gửi đánh giá</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mô tả Chi tiết -->
                <div class="bg-white p-4 rounded-3 shadow-sm mb-5">
                    <h4 class="fw-bold mb-3" style="color: #4e342e;">Thông tin chi tiết</h4>
                    <p>Nội dung chi tiết về sản phẩm, bao gồm thành phần, cách sử dụng, công dụng, v.v. Cần thêm tab để tách biệt nội dung này với phần đánh giá nếu có.</p>
                </div>

                <!-- Sản phẩm liên quan -->
                <h4 class="fw-bold mb-3" style="color: #4e342e;">Sản phẩm liên quan</h4>
                <div class="row g-3">
                    <?php foreach ($relatedProducts as $rp): ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="border p-3 bg-white rounded-3 text-center shadow-sm">
                                <img src="../images/sanpham/<?php echo $rp['Img']; ?>" class="img-fluid rounded mb-2">
                                <h6 class="mt-2 fw-bold" style="color: #5D4037;"><?php echo $rp['Title']; ?></h6>
                                <p class="text-muted mb-0"><?php echo number_format($rp['Price'], 0, ",", "."); ?> VND</p>
                                <a href="detail.php?Id=<?php echo $product['Id']; ?>" class="btn btn-sm mt-2" style="background-color: #f7f5f2; color: #A0522D;">Chi tiết</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>

            <!-- Cột Thanh bên phải -->
            <div class="col-md-3">
                <div class="bg-white p-4 rounded-3 shadow-sm mb-4">
                    <h5 class="fw-bold mb-3" style="color: #4e342e;">Sản phẩm nổi bật</h5>
                    <div class="list-group">
                        <?php foreach ($featuredProducts as $fp): ?>
                            <div class="d-flex mb-3 align-items-center border-bottom pb-2">
                                <img src="../images/sanpham/<?php echo $fp['Img']; ?>" style="width:60px; height:60px; object-fit:cover; border-radius: 5px; margin-right:10px;">
                                <div>
                                    <h6 class="mb-0 fw-semibold" style="color: #5D4037;"><?php echo $fp['Title']; ?></h6>
                                    <p class="mb-0 text-muted small"><?php echo number_format($fp['Price'], 0, ",", "."); ?> VND</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>