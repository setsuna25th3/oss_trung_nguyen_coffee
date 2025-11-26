<?php
// Tải kết nối CSDL
require_once 'db_connect.php';

// Giả định chúng ta đang xem sản phẩm có ID là 1
$product_id = 1; 

// Lấy thông tin người dùng đang đăng nhập (TẠM THỜI: SỬ DỤNG ID 1 để test)
$logged_in_customer_id = 1; 
$is_logged_in = ($logged_in_customer_id > 0);

$product_name = 'Sản phẩm không rõ tên'; 
$reviews = [];
$average_rating = 0;
$total_reviews = 0;
$rating_distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

// KIỂM TRA ĐÁNH GIÁ  
$existing_review = null;
$review_id = null; // ID của đánh giá cũ (nếu có)
$initial_rating = 0;
$initial_comment = '';
$form_title = "Chia sẻ ý kiến của bạn";
$button_text = "Gửi Đánh Giá";

if ($is_logged_in) {
    $sql_check_review = "
        SELECT Id, Rating, Comment 
        FROM productreview 
        WHERE ProductId = ? AND CustomerId = ?
    ";
    $stmt_check = $conn->prepare($sql_check_review);
    $stmt_check->bind_param("ii", $product_id, $logged_in_customer_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0) {
        // Tìm thấy đánh giá cũ, chuyển sang chế độ CHỈNH SỬA
        $existing_review = $result_check->fetch_assoc();
        $review_id = $existing_review['Id'];
        $initial_rating = $existing_review['Rating'];
        $initial_comment = $existing_review['Comment'];
        
        $form_title = "Chỉnh sửa Đánh Giá Của Bạn";
        $button_text = "Cập Nhật Đánh Giá";
    }
    $stmt_check->close();
}


// --- Lấy tên sản phẩm ---
$sql_product = "SELECT Title FROM product WHERE Id = ?";
$stmt_product = $conn->prepare($sql_product);
$stmt_product->bind_param("i", $product_id);
$stmt_product->execute();
$result_product = $stmt_product->get_result();
if ($p_row = $result_product->fetch_assoc()) {
    $product_name = $p_row['Title'];
}
$stmt_product->close();

// --- 1. Lấy dữ liệu đánh giá, tính trung bình và phân bố ---
$sql_summary = "
    SELECT 
        COUNT(Id) AS total_reviews, 
        AVG(Rating) AS average_rating,
        Rating,
        COUNT(Rating) AS count_by_rating
    FROM productreview 
    WHERE ProductId = ?
    GROUP BY Rating
    WITH ROLLUP
";
$stmt_summary = $conn->prepare($sql_summary);
$stmt_summary->bind_param("i", $product_id);
$stmt_summary->execute();
$result_summary = $stmt_summary->get_result();

while ($row = $result_summary->fetch_assoc()) {
    if ($row['Rating'] !== null) {
        $rating_distribution[$row['Rating']] = $row['count_by_rating'];
    } else {
        $total_reviews = $row['total_reviews'];
        $average_rating = round($row['average_rating'], 1);
    }
}
$stmt_summary->close();

// Lấy danh sách đánh giá chi tiết
$sql_reviews = "
    SELECT 
        pr.Rating, 
        pr.Comment, 
        pr.CreatedAt, 
        c.FirstName, 
        c.LastName
    FROM productreview pr
    JOIN customer c ON pr.CustomerId = c.Id
    WHERE pr.ProductId = ?
    ORDER BY pr.CreatedAt DESC
";
$stmt_reviews = $conn->prepare($sql_reviews);
$stmt_reviews->bind_param("i", $product_id);
$stmt_reviews->execute();
$result_reviews = $stmt_reviews->get_result();

while ($row = $result_reviews->fetch_assoc()) {
    $row['user_name'] = htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']);
    $reviews[] = $row;
}
$stmt_reviews->close();


// --- 2. Xử lý gửi/cập nhật đánh giá (Logic POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review']) && $is_logged_in) {
    $customer_id = $logged_in_customer_id; 
    $rating = (int)$_POST['rating'];
    $comment = $_POST['comment'] ?? '';
    
    if ($rating < 1 || $rating > 5) {
        $error = "Vui lòng chọn số sao hợp lệ.";
    } else {
        if ($existing_review) {
            // UPDATE
            $sql_action = "
                UPDATE productreview 
                SET Rating = ?, Comment = ?, CreatedAt = NOW() 
                WHERE Id = ?
            ";
            $stmt_action = $conn->prepare($sql_action);
            // Binding: Rating(int), Comment(string), Id(int)
            $stmt_action->bind_param("isi", $rating, $comment, $review_id);
            
        } else {
            //INSERT (Gửi mới)
            $sql_action = "
                INSERT INTO productreview (ProductId, CustomerId, Rating, Comment, CreatedAt) 
                VALUES (?, ?, ?, ?, NOW())
            ";
            $stmt_action = $conn->prepare($sql_action);
            $stmt_action->bind_param("iiis", $product_id, $customer_id, $rating, $comment);
        }
        
        if ($stmt_action->execute()) {
            // Chuyển hướng để tải lại trang và cập nhật UI
            header("Location: product_review.php?product_id=$product_id"); 
            exit();
        } else {
            $error = "Lỗi xử lý đánh giá: " . $conn->error;
        }
        $stmt_action->close();
    }
}

// Hàm hỗ trợ hiển thị sao (Không đổi)
function display_stars($rating) {
    $stars = '';
    $rating = round($rating); 
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $stars .= '<span class="star filled">★</span>';
        } else {
            $stars .= '<span class="star empty">★</span>';
        }
    }
    return $stars;
}

// Hàm hỗ trợ tạo thanh tiến trình (Không đổi)
function render_rating_bar($count, $total) {
    $percentage = $total > 0 ? ($count / $total) * 100 : 0;
    return "
        <div class='progress-bar-container'>
            <div class='progress-bar' style='width: {$percentage}%;'></div>
        </div>
        <span class='review-count'>({$count})</span>
    ";
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đánh Giá Sản Phẩm - <?php echo $product_name; ?></title>
    <style>
        .review-section { max-width: 900px; margin: 30px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; font-family: Arial, sans-serif; }
        .submit-button { background-color: #8b4513 !important; color: white !important; }

        /* Tóm tắt */
        .summary-container { display: flex; gap: 30px; margin-bottom: 30px; }
        .rating-summary { flex-shrink: 0; text-align: center; border-right: 1px solid #eee; padding-right: 30px; }
        .average-score { font-size: 3em; font-weight: bold; color: #8b4513; margin: 0; }
        .star { font-size: 1.2em; }
        .star.filled { color: gold; }
        .star.empty { color: lightgray; }
        
        /* Phân bố */
        .distribution-chart { flex-grow: 1; padding-left: 10px; }
        .distribution-row { display: flex; align-items: center; margin-bottom: 5px; font-size: 0.9em; }
        .rating-label { width: 40px; text-align: right; margin-right: 10px; }
        .progress-bar-container { flex-grow: 1; height: 10px; background-color: #f0f0f0; border-radius: 5px; overflow: hidden; margin-right: 10px; }
        .progress-bar { height: 100%; background-color: gold; }
        .review-count { width: 40px; text-align: left; }
        
        /* Form */
        .review-form label { display: block; margin-top: 15px; font-weight: bold; }
        .review-form input[type="text"], .review-form textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .review-form textarea { resize: vertical; }

        /* STAR RATING INTERACTIVE (CSS ONLY) */
        .rating-stars { 
            display: flex; 
            justify-content: flex-start;
            direction: rtl; 
            font-size: 30px;
            margin-top: 5px;
            width: 100%; 
        }
        .rating-stars input { display: none; }
        .rating-stars label { 
            color: lightgray; 
            cursor: pointer; 
            padding: 0 1px;
            transition: color 0.2s;
        }
        .rating-stars label:hover,
        .rating-stars label:hover ~ label,
        .rating-stars input:checked ~ label {
            color: gold;
        }
        
        /* Review List */
        .review-item { border-bottom: 1px dashed #eee; padding: 15px 0; }
        .review-item:last-child { border-bottom: none; }
        .review-meta { display: flex; justify-content: space-between; font-size: 0.9em; color: #666; margin-bottom: 5px; }
        .reviewer-name { font-weight: bold; color: #333; }
    </style>
</head>
<body>

<div class="review-section">
    <h1>Đánh Giá cho sản phẩm: **<?php echo htmlspecialchars($product_name); ?>**</h1>
    
    <div class="summary-container">
        <div class="rating-summary">
            <p style="color:#666; margin-bottom: 5px;">Đánh giá trung bình</p>
            <p class="average-score"><?php echo $average_rating; ?></p>
            <p><?php echo display_stars($average_rating); ?></p>
            <p style="font-size: 0.9em; color: #888;">(<?php echo $total_reviews; ?> lượt đánh giá)</p>
        </div>
        <div class="distribution-chart">
            <?php foreach (range(5, 1) as $star): ?>
                <div class="distribution-row">
                    <span class="rating-label"><?php echo $star; ?> sao</span>
                    <?php echo render_rating_bar($rating_distribution[$star], $total_reviews); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <hr>

    <div class="review-form">
        <h3><?php echo $form_title; ?></h3>
        <?php if (!$is_logged_in): ?>
            <div style="padding: 15px; background-color: #ffeaea; border: 1px solid #f00; color: #f00; border-radius: 4px;">
                Vui lòng **<a href="form_login.php">Đăng nhập</a>** để gửi đánh giá.
            </div>
        <?php else: ?>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <form method="POST" action="">
                
                <label>Đánh giá sao:</label>
                <div class="rating-stars">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input 
                            type="radio" 
                            id="star-<?php echo $i; ?>" 
                            name="rating" 
                            value="<?php echo $i; ?>" 
                            <?php echo ($initial_rating == $i) ? 'checked' : ''; ?> 
                            required>
                        <label for="star-<?php echo $i; ?>">★</label>
                    <?php endfor; ?>
                </div>
                
                <label for="comment">Bình luận (Tối đa 500 ký tự):</label>
                <textarea 
                    id="comment" 
                    name="comment" 
                    rows="4" 
                    required 
                    maxlength="500" 
                    placeholder="Viết đánh giá của bạn tại đây..."><?php echo htmlspecialchars($initial_comment); ?></textarea>
                
                <button type="submit" name="submit_review" class="submit-button" style="margin-top: 15px; padding: 10px 20px; border: none; cursor: pointer;">
                    <?php echo $button_text; ?>
                </button>
            </form>
        <?php endif; ?>
    </div>

    <hr>

    <div class="review-list">
        <h3><?php echo $total_reviews; ?> Đánh Giá</h3>
        <?php if ($total_reviews > 0): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review-item">
                    <div class="review-meta">
                        <span class="reviewer-name"><?php echo $review['user_name']; ?></span>
                        <span class="review-date">
                            <?php 
                                $date_obj = new DateTime($review['CreatedAt']);
                                echo $date_obj->format('H:i d/m/Y'); 
                            ?>
                        </span>
                    </div>
                    <p><?php echo display_stars($review['Rating']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($review['Comment'])); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên!</p>
        <?php endif; ?>
    </div>

</div>
</body>
</html>