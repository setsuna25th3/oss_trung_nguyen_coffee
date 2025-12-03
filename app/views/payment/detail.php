<?php
session_start();
require_once '../../controllers/PaymentController.php';
if (!isset($_SESSION['CustomerId'])) {
    header("Location: ../customer/sign_in.php");
    exit();
}
$id = $_GET['id'] ?? 0;
if (!$id) die('<div class="alert alert-danger text-center">ID đơn hàng không hợp lệ!</div>');
$paymentController = new PaymentController();
$payment = $paymentController->getPaymentById($id);
if (!$payment) die('<div class="alert alert-danger text-center">Đơn hàng không tồn tại!</div>');
$payment['PaymentDetail'] = $payment['PaymentDetail'] ?? [];
include '../header.php';
?>
<style>
    body,
    html {
        font-family: 'Poppins', sans-serif;
        background: #f4f6f9;
        /* Nền nhẹ nhàng hơn */
        margin: 0;
        padding: 0;
    }

    .page-header {
        padding: 80px 0;
        /* Gradient hiện đại */
        background: linear-gradient(135deg, #ffc107, #ff9800);
        color: #fff;
        text-align: center;
        position: relative;
        /* Góc bo lớn hơn */
        border-radius: 0 0 40px 40px;
        /* Shadow nổi bật */
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .page-header h2 {
        font-size: 48px;
        font-weight: 800;
        margin: 0;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
    }

    .invoice-card {
        background: #fff;
        border-radius: 25px;
        /* Góc bo lớn hơn */
        padding: 40px;
        margin: 40px 0;
        /* Shadow mượt mà hơn */
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .invoice-card:hover {
        transform: translateY(-5px);
    }

    .section-title {
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 30px;
        /* Border nhẹ nhàng hơn */
        border-left: 5px solid #ffb300;
        padding-left: 15px;
        color: #333;
        letter-spacing: 0.5px;
    }

    /* Style cho bảng thông tin hóa đơn (Label/Value) */
    .invoice-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 15px;
        /* Khoảng cách giữa các dòng */
    }

    .invoice-table th,
    .invoice-table td {
        padding: 18px 20px;
        border: none;
        vertical-align: middle;
    }

    .invoice-table tr {
        background: #ffffff;
        border: 1px solid #eee;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease-in-out;
    }

    .invoice-table tr:hover {
        background: #fffcf5;
    }

    .invoice-table th {
        background: #f8f9fa;
        /* Nền nhẹ cho label */
        color: #555;
        font-weight: 600;
        width: 35%;
        border-radius: 12px 0 0 12px;
    }

    .invoice-table td {
        font-weight: 500;
        color: #333;
        border-radius: 0 12px 12px 0;
    }

    .text-warning-custom {
        color: #ff9800 !important;
        font-size: 1.25rem;
        font-weight: 700 !important;
    }

    /* Style cho bảng chi tiết sản phẩm (Dạng Grid/List) */
    .products-table th {
        background: #343a40;
        color: #ffc107;
        font-weight: 600;
        padding: 15px;
        text-align: center;
    }

    .products-table td {
        vertical-align: middle;
        padding: 15px 8px;
        text-align: center;
    }

    .products-table img.product-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 15px;
        /* Góc bo hiện đại */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border: 3px solid #f0f0f0;
    }

    .btn-custom {
        padding: 12px 35px;
        border-radius: 50px;
        /* Nút tròn, hiện đại */
        background: #ffb300;
        color: #fff !important;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
        margin-top: 30px;
        letter-spacing: 0.5px;
        border: none;
    }

    .btn-custom:hover {
        background: #ff9800;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 165, 0, 0.4);
    }

    .btn-sm-view {
        /* Style riêng cho nút Xem chi tiết sản phẩm */
        background: #3498db;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 14px;
        color: #fff !important;
        text-decoration: none;
        transition: background 0.3s;
    }

    .btn-sm-view:hover {
        background: #2980b9;
    }

    @media(max-width:768px) {
        .page-header {
            padding: 40px 0;
            border-radius: 0 0 20px 20px;
        }

        .page-header h2 {
            font-size: 32px;
        }

        .invoice-card {
            padding: 20px;
            margin: 20px 0;
            border-radius: 15px;
        }

        .section-title {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 10px;
            font-size: 14px;
            display: block;
            width: 100%;
            border-radius: 0;
        }

        .invoice-table tr {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
            display: block;
        }

        .products-table th,
        .products-table td {
            padding: 10px;
            font-size: 13px;
        }

        .products-table img.product-img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
        }
    }
</style>
<main class="container my-5">
    <div class="page-header">
        <h2>Chi tiết Thanh Toán</h2>
    </div>
    <div class="invoice-card">
        <h3 class="section-title">📝 Thông tin hóa đơn</h3>
        <table class="invoice-table">
            <tr>
                <th>Mã hóa đơn</th>
                <td><?= htmlspecialchars($payment['Id']) ?></td>
            </tr>
            <tr>
                <th>Mã khách hàng</th>
                <td><?= htmlspecialchars($payment['CustomerId']) ?></td>
            </tr>
            <tr>
                <th>Họ & Tên</th>
                <td><?= htmlspecialchars($payment['LastName']) . " " . htmlspecialchars($payment['FirstName']) ?></td>
            </tr>
            <tr>
                <th>Điện thoại</th>
                <td><?= htmlspecialchars($payment['Phone']) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($payment['Email']) ?></td>
            </tr>
            <tr>
                <th>Ngày tạo</th>
                <td><?= date("H:i, d/m/Y", strtotime($payment['CreatedAt'])) ?></td>
            </tr>
            <tr>
                <th>Tổng tiền thanh toán</th>
                <td class="text-warning-custom"><?= number_format($payment['Total'], 0, ',', '.') ?> VNĐ</td>
            </tr>
        </table>
    </div>

    <div class="invoice-card">
        <h3 class="section-title">🛒 Chi tiết sản phẩm</h3>
        <div class="table-responsive">
            <table class="products-table table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 25%;">Tên sản phẩm</th>
                        <th style="width: 15%;">Hình ảnh</th>
                        <th style="width: 15%;">Giá</th>
                        <th style="width: 10%;">Số lượng</th>
                        <th style="width: 20%;">Thành tiền</th>
                        <th style="width: 15%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payment['PaymentDetail'] as $detail):
                        $totalDetail = $detail['Price'] * $detail['Quantity'];
                    ?>
                        <tr>
                            <td class="text-start"><?= htmlspecialchars($detail['ProductName']) ?></td>
                            <td>
                                <?php if (!empty($detail['ImageUrl'])): ?>
                                    <img src="../../img/SanPham/<?= htmlspecialchars($detail['ImageUrl']) ?>" class="product-img" alt="<?= htmlspecialchars($detail['ProductName']) ?>">
                                <?php endif; ?>
                            </td>
                            <td><?= number_format($detail['Price'], 0, ',', '.') ?> VNĐ</td>
                            <td><?= $detail['Quantity'] ?></td>
                            <td class="fw-bold text-success"><?= number_format($totalDetail, 0, ',', '.') ?> VNĐ</td>
                            <td><a href="../product/detail.php?id=<?= $detail['ProductId'] ?>" class="btn-sm-view">Xem</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="text-center">
            <a href="../payment/index.php" class="btn-custom">⬅️ Quay lại danh sách</a>
        </div>
    </div>
</main>
<?php include '../footer.php'; ?>