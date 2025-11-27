<?php
require_once __DIR__ . "/../Authentication/db_connect.php";

class ProductModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        if (!$conn) {
            die("Không thể kết nối database trong ProductModel");
        }
        $this->conn = $conn;
    }

    // ================================
    // LẤY TẤT CẢ SẢN PHẨM, có search, lọc category và sắp xếp
    // ================================
    public function getAll($search = "", $categoryId = "", $order = "")
    {
        $sql = "SELECT p.*, c.title AS category_title 
                FROM product p
                LEFT JOIN category c ON p.categoryid = c.id
                WHERE 1";

        $params = [];
        $types = "";

        if (!empty($search)) {
            $sql .= " AND p.name LIKE ?";
            $params[] = "%$search%";
            $types .= "s";
        }

        if (!empty($categoryId)) {
            $sql .= " AND p.categoryid = ?";
            $params[] = $categoryId;
            $types .= "i";
        }

        if (!empty($order)) {
            if ($order === "price_asc") {
                $sql .= " ORDER BY p.price ASC";
            } elseif ($order === "price_desc") {
                $sql .= " ORDER BY p.price DESC";
            } elseif ($order === "rate_desc") {
                $sql .= " ORDER BY p.rate DESC";
            }
        }

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            die("Lỗi SQL getAll(): " . mysqli_error($this->conn) . "<br>Query: " . $sql);
        }

        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    // ================================
    // LẤY DANH MỤC
    // ================================
    public function getCategories()
    {
        $sql = "SELECT * FROM category";
        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            die("Lỗi SQL getCategories(): " . mysqli_error($this->conn));
        }

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    // ================================
    // LẤY CHI TIẾT SẢN PHẨM THEO ID
    // ================================
    public function getById($id)
    {
        $sql = "SELECT p.*, c.title AS category_title
                FROM product p
                LEFT JOIN category c ON p.categoryid = c.id
                WHERE p.id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            die("Lỗi SQL getById(): " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($result);
    }

    // ================================
    // LẤY SẢN PHẨM BÁN CHẠY
    // ================================
    public function getBestSeller($limit = 5)
    {
        $sql = "SELECT p.*, c.title AS category_title 
                FROM product p
                LEFT JOIN category c ON p.categoryid = c.id
                ORDER BY p.sold DESC
                LIMIT ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            die("Lỗi SQL getBestSeller(): " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    // ================================
    // LẤY SẢN PHẨM MỚI NHẤT
    // ================================
    public function getNewest($limit = 5)
    {
        $sql = "SELECT p.*, c.name AS category_name 
                FROM product p
                LEFT JOIN category c ON p.category_id = c.id
                ORDER BY p.created_at DESC
                LIMIT ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            die("Lỗi SQL getNewest(): " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    // ================================
    // LẤY SẢN PHẨM NỔI BẬT (rate >= 4)
    // ================================
    public function getFeatured($limit = 5)
    {
        $sql = "SELECT p.*, c.name AS category_name 
                FROM product p
                LEFT JOIN category c ON p.category_id = c.id
                WHERE p.rate >= 4
                ORDER BY p.rate DESC
                LIMIT ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            die("Lỗi SQL getFeatured(): " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }
}
