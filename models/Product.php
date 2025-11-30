<?php
// Đường dẫn: Giả định Product.php nằm trong models/
require_once dirname(__DIR__, 1) . '/db_connect.php'; 

/**
 * Class Customer (Giữ nguyên)
 */
class Customer {
    public $Id;
    public $FirstName;
    public $LastName;
    // ... (các thuộc tính và hàm)
    public static function findById($id) {
        // ... (Logic tìm khách hàng)
    }
}

class Product {
    
    /**
     * Lấy danh sách đánh giá của sản phẩm theo ID.
     * ĐÃ SỬA: Bổ sung pr.CustomerId để fix lỗi Undefined array key trong View.
     */
    public static function getReviewsByProductId($productId): array {
        global $conn;
        $reviews = [];
        
        $sql = "SELECT 
                    pr.CustomerId,  -- Cột đã được thêm
                    pr.CreatedAt, 
                    pr.Rating, 
                    pr.Comment, 
                    c.FirstName, 
                    c.LastName
                FROM 
                    productreview pr
                JOIN 
                    customer c ON pr.CustomerId = c.Id
                WHERE 
                    pr.ProductId = ?
                ORDER BY 
                    pr.CreatedAt DESC";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            error_log("SQL Prepare Error in getReviewsByProductId: " . $conn->error);
            return $reviews;
        }

        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        $stmt->close();
        return $reviews;
    }

    /**
     * BỔ SUNG: Lấy đánh giá cũ của khách hàng cho sản phẩm (nếu có).
     */
    public static function getExistingReview(int $productId, int $customerId) {
        global $conn;
        $sql = "SELECT Id, Rating, Comment FROM productreview WHERE ProductId = ? AND CustomerId = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ii", $productId, $customerId);
            $stmt->execute();
            $result = $stmt->get_result();
            $review = $result->fetch_assoc();
            $stmt->close();
            return $review; // Trả về mảng hoặc null
        }
        return null;
    }

    /**
     * THAY THẾ: Thêm mới HOẶC Cập nhật đánh giá (sử dụng tên bảng productreview).
     */
    public static function saveReview(?int $reviewId, int $productId, int $customerId, int $rating, string $comment): bool {
        global $conn;

        $comment = substr(trim($comment), 0, 500); 
        $success = false;
        
        if ($reviewId !== null && $reviewId > 0) {
            // CẬP NHẬT ĐÁNH GIÁ CŨ
            $sql = "UPDATE productreview 
                    SET Rating = ?, Comment = ?, CreatedAt = NOW() 
                    WHERE Id = ? AND ProductId = ? AND CustomerId = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                // 'i' cho Rating (integer), 's' cho Comment (string), 'i' cho Id, ProductId, CustomerId
                $stmt->bind_param("isiii", $rating, $comment, $reviewId, $productId, $customerId);
                $success = $stmt->execute();
                $stmt->close();
            }
        } else {
            // THÊM ĐÁNH GIÁ MỚI
            $sql = "INSERT INTO productreview (ProductId, CustomerId, Rating, Comment, CreatedAt) 
                    VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("iiis", $productId, $customerId, $rating, $comment);
                $success = $stmt->execute();
                $stmt->close();
            }
        }
        
        // Cập nhật lại AverageRating của sản phẩm
        if ($success) {
            self::updateProductAverageRating($productId);
        }

        return $success;
    }

    /**
     * Cập nhật điểm đánh giá trung bình của sản phẩm.
     */
    public static function updateProductAverageRating(int $productId) {
        global $conn;

        $sql_avg = "SELECT AVG(Rating) as average_rating FROM productreview WHERE ProductId = ?";
        $stmt_avg = $conn->prepare($sql_avg);
        $stmt_avg->bind_param("i", $productId);
        $stmt_avg->execute();
        $result_avg = $stmt_avg->get_result();
        $row_avg = $result_avg->fetch_assoc();
        $stmt_avg->close();

        $newAverageRating = $row_avg['average_rating'] ?? 0;
        
        $sql_update = "UPDATE product SET Rate = ? WHERE Id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("di", $newAverageRating, $productId); 
        $stmt_update->execute();
        $stmt_update->close();
    }
}
?>