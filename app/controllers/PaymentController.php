<?php
require_once __DIR__ . '/ProductController.php';

class PaymentController
{
    protected $productController;

    public function __construct()
    {
        $this->productController = new ProductController();
    }

    public function getPaymentById(int $paymentId)
    {
        global $hostname, $username, $password, $dbname, $port;
        $db = new mysqli($hostname, $username, $password, $dbname, $port);
        if ($db->connect_errno) die("DB lỗi: " . $db->connect_error);

        // Lấy payment + customer
        $sql = "SELECT pay.*, c.FirstName, c.LastName, c.Phone, c.Email
                FROM payment pay
                JOIN customer c ON pay.CustomerId = c.Id
                WHERE pay.Id = $paymentId LIMIT 1";
        $res = $db->query($sql);
        if ($res->num_rows == 0) return null;
        $payment = $res->fetch_assoc();

        // Lấy chi tiết paymentdetail
        $sqlDetail = "SELECT * FROM paymentdetail WHERE PaymentId = $paymentId";
        $resDetail = $db->query($sqlDetail);

        $paymentDetails = [];
        if ($resDetail && $resDetail->num_rows > 0) {
            while ($row = $resDetail->fetch_assoc()) {
                $product = $this->productController->getProductById($row['ProductId']);
                $paymentDetails[] = [
                    'PaymentDetailId' => $row['Id'],
                    'ProductId' => $row['ProductId'],
                    'ProductName' => $product->Title ?? 'Sản phẩm đã xóa',
                    'ImageUrl' => $product->Img ?? '',
                    'Price' => $row['Price'],
                    'Quantity' => $row['Quantity'],
                    'Total' => $row['Price'] * $row['Quantity']
                ];
            }
        }

        $payment['PaymentDetail'] = $paymentDetails;
        $db->close();
        return $payment;
    }
}
