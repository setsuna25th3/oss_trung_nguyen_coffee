<?php
// Bắt đầu session nếu chưa bắt đầu
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. REQUIRE CÁC FILE CẦN THIẾT
require_once '../../db_connect.php'; 
require_once '../../models/Product.php'; 

// Lấy ID sản phẩm từ URL
$productId = (int)($_GET['Id'] ?? 0);

if ($productId <= 0) {
    if (function_exists('set_flash_message')) {
        set_flash_message("Không tìm thấy sản phẩm cần đánh giá.", "danger");
    }
    header('Location: ../index.php');
    exit();
}

// 2. LẤY THÔNG TIN SẢN PHẨM (Title, Rate VÀ Img)
$productTitle = 'Sản phẩm không xác định';
$productImg = 'default.jpg'; 
$averageRating = 0;
global $conn;

// SỬA: Thêm cột Img vào truy vấn để hiển thị ảnh
$stmt = $conn->prepare("SELECT Title, Rate, Img FROM product WHERE Id = ?");
if ($stmt) {
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $productData = $result->fetch_assoc();
        $productTitle = $productData['Title'];
        $productImg = $productData['Img']; // Lấy tên file ảnh
        $averageRating = round($productData['Rate'] ?? 0, 1);
    }
    $stmt->close();
}


// 3. LẤY DANH SÁCH ĐÁNH GIÁ VÀ KIỂM TRA ĐĂNG NHẬP
$isLoggedIn = isset($_SESSION['customer_id']);
$customerId = $_SESSION['customer_id'] ?? null;
$currentUrl = urlencode($_SERVER['REQUEST_URI']);

// Lấy danh sách đánh giá (Đã đảm bảo có CustomerId trong Model)
$reviews = Product::getReviewsByProductId($productId);
$totalReviews = count($reviews);

// 4. KIỂM TRA ĐÁNH GIÁ ĐÃ CÓ VÀ ĐẶT BIẾN FORM
$reviewId = null;
$initialRating = 5; 
$initialComment = '';
$formTitle = "Viết đánh giá của bạn";
$buttonText = "Gửi Đánh Giá";

if ($isLoggedIn && $customerId !== null) {
    $existingReview = Product::getExistingReview($productId, $customerId);

    if ($existingReview) {
        $reviewId = $existingReview['Id'];
        $initialRating = $existingReview['Rating'];
        $initialComment = $existingReview['Comment'];
        $formTitle = "Chỉnh sửa đánh giá của bạn";
        $buttonText = "Cập Nhật Đánh Giá";
    }
}

/**
 * Hàm hiển thị sao 
 */
if (!function_exists('display_stars')) {
    function display_stars($rating) {
        $output = '';
        $rating = (int)$rating;
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $output .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $output .= '<i class="far fa-star text-warning"></i>';
            }
        }
        return $output;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đánh giá sản phẩm - <?= htmlspecialchars($productTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../../css/style.css"> 
    <style>
        /* CSS cụ thể cho trang đánh giá */
        .review-container {
            max-width: 900px;
            margin: 0 auto; 
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .rating-stars {
            font-size: 2rem;
            color: #ffc107; 
        }
        .rating-stars i {
            cursor: pointer;
            transition: color 0.2s;
        }
        .rating-stars i.far {
            color: #e9ecef;
        }
        .rating-stars i:hover,
        .rating-stars i.selected {
            color: #ffc107;
        }
        .comments-list {
            margin-top: 20px;
        }
        .comment-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        .comment-item:last-child {
            border-bottom: none;
        }
        .comment-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .comment-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 1px solid #eee;
        }
        .comment-info .name {
            font-weight: bold;
            color: #333;
            display: block;
            margin-bottom: 3px;
        }
        .comment-rating i {
            font-size: 0.9rem;
        }
    </style>
</head>

<body style="background-color: #f7f5f2;">
   <?php include '../header.php'; ?>
    
    <div class="container py-5">
        <?php 
        if (function_exists('display_flash_message')) {
            display_flash_message(); 
        }
        ?>

        <div class="review-container">
            <h3 class="fw-bold mb-3" style="color: #4e342e;">Đánh giá sản phẩm: <?= htmlspecialchars($productTitle) ?></h3>
            
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                <span class="rating-overall fw-bold fs-4 me-3" style="color: #4e342e;"><?= $averageRating ?>/5</span>
                <div class="rating-stars me-3">
                    <?php 
                    $fullStars = floor($averageRating);
                    $halfStar = ($averageRating - $fullStars) >= 0.5;
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $fullStars) {
                            echo '<i class="fas fa-star text-warning"></i>';
                        } elseif ($i == $fullStars + 1 && $halfStar) {
                            echo '<i class="fas fa-star-half-alt text-warning"></i>';
                        } else {
                            echo '<i class="far fa-star text-warning"></i>';
                        }
                    }
                    ?>
                </div>
                <span class="text-muted">(<?= $totalReviews ?> đánh giá)</span>
            </div>

            <div class="d-flex align-items-center mb-5 p-3 border rounded-3 bg-white shadow-sm">
                <img src="../../images/sanpham/<?= htmlspecialchars($productImg) ?>" 
                    alt="<?= htmlspecialchars($productTitle) ?>" 
                    style="width: 80px; height: 80px; object-fit: cover; margin-right: 20px; border-radius: 5px; border: 1px solid #eee;">
                <div>
                    <h5 class="fw-bold mb-0" style="color: #4e342e;"><?= htmlspecialchars($productTitle) ?></h5>
                    <span class="text-muted small">ID sản phẩm: <?= $productId ?></span>
                </div>
            </div>
            <?php if ($isLoggedIn): ?>
                <div class="mb-5 p-4 border rounded-3 bg-light">
                    <h4 class="fw-semibold mb-3 text-primary"><?= $formTitle ?></h4>
                    <form action="product_review_process.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $productId ?>">
                        <input type="hidden" name="review_id" value="<?= htmlspecialchars($reviewId) ?>"> 
                        <input type="hidden" name="rating" id="rating-input" value="<?= $initialRating ?>"> 

                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn số sao:</label>
                            <div class="rating-stars" id="rating-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star" data-rating="<?= $i ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="comment" class="form-label fw-bold">Nội dung đánh giá:</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4" required placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..."><?= htmlspecialchars($initialComment) ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-warning fw-bold text-dark"><?= $buttonText ?></button>
                    </form>
                </div>
            <?php else: ?>
                <div class="alert alert-warning text-center mb-5">
                    Vui lòng <a href="../customer/login.php?returnUrl=<?= $currentUrl ?>" class="alert-link fw-bold">Đăng nhập</a> để có thể đánh giá sản phẩm.
                </div>
            <?php endif; ?>


            <h4 class="fw-bold mb-3" style="color: #4e342e;">Các đánh giá khác (<?= $totalReviews ?>)</h4>
            <div class="comments-list">
                <?php if ($totalReviews > 0): ?>
                    <?php foreach ($reviews as $review): 
                        $reviewDate = $review['CreatedAt'] ?? date('Y-m-d H:i:s');
                        $fullName = htmlspecialchars($review['LastName'] . ' ' . $review['FirstName']);
                    ?>
                        <div class="comment-item">
                            <div class="comment-header">
                                <img src="../../images/avatar/default-avatar.jpg" class="comment-avatar" alt="Avatar">
                                <div class="comment-info">
                                    <span class="name"><?= $fullName ?></span>
                                    <div class="comment-rating">
                                        <?= display_stars($review['Rating']) ?> 
                                    </div>
                                    <span class="date d-block text-muted small">Ngày: <?= date('d/m/Y', strtotime($reviewDate)) ?></span>
                                </div>
                            </div>
                            <p class="comment-content text-dark"><?= nl2br(htmlspecialchars($review['Comment'])) ?></p>
                            <?php if ($isLoggedIn && isset($review['CustomerId']) && $review['CustomerId'] == $customerId): ?>
                                <a href="product_review.php?Id=<?= $productId ?>" class="text-decoration-none small text-primary fw-bold">Chỉnh sửa</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info text-center">
                        Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên!
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

   <?php include '../../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JS Logic để chọn sao (Giữ nguyên)
        document.addEventListener('DOMContentLoaded', function() {
            const ratingStars = document.getElementById('rating-stars');
            if (!ratingStars) return;

            const ratingInput = document.getElementById('rating-input');
            const stars = ratingStars.querySelectorAll('i');

            function updateStars(rating) {
                rating = parseInt(rating); 
                stars.forEach(s => {
                    const sRating = parseInt(s.getAttribute('data-rating'));
                    if (sRating <= rating) {
                        s.classList.remove('far');
                        s.classList.add('fas', 'selected');
                    } else {
                        s.classList.remove('fas', 'selected');
                        s.classList.add('far');
                    }
                });
            }

            updateStars(ratingInput.value);

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const clickRating = this.getAttribute('data-rating');
                    ratingInput.value = clickRating;
                    updateStars(clickRating);
                });

                star.addEventListener('mouseover', function() {
                    const hoverRating = this.getAttribute('data-rating');
                    stars.forEach(s => {
                        const sRating = parseInt(s.getAttribute('data-rating'));
                        if (sRating <= hoverRating) {
                            s.classList.remove('far');
                            s.classList.add('fas');
                        } else {
                            if (!s.classList.contains('selected')) {
                                s.classList.remove('fas');
                                s.classList.add('far');
                            }
                        }
                    });
                });

                ratingStars.addEventListener('mouseout', function() {
                    updateStars(ratingInput.value);
                });
            });
        });
    </script>
</body>

</html>