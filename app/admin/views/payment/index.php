<?php
if (session_status() == PHP_SESSION_NONE) session_start();

// Check login
if (!isset($_SESSION['CustomerId'])) {
    header('Location: ../../../views/home/index.php');
    exit();
}

require_once __DIR__ . '/../../../controllers/CustomerController.php';
require_once __DIR__ . '/../../controllers/PaymentAdminController.php';

$customerController = new CustomerController();
$customer = $customerController->getCustomerById($_SESSION['CustomerId']);

if (!$customer || !$customer->Role) {
    header('Location: ../../../views/home/index.php');
    exit();
}

$paymentController = new PaymentAdminController();
$payments = $paymentController->getAllPayments();
$paymentsJson = json_encode($payments, JSON_UNESCAPED_UNICODE);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý thanh toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Quản lý thanh toán</h1>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Khách hàng</th>
                        <th>Cửa hàng</th>
                        <th>Tổng tiền</th>
                        <th>Vận chuyển</th>
                        <th>Mã tracking</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($payments)): ?>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?= $payment->Id ?></td>
                                <td><?= $payment->CustomerId ?></td>
                                <td><?= $payment->StoreId ?></td>
                                <td><?= number_format($payment->Total, 0, ",", ".") ?>₫</td>
                                <td><?= htmlspecialchars($payment->Carrier) ?></td>
                                <td><?= htmlspecialchars($payment->TrackingCode) ?></td>
                                <td><?= htmlspecialchars($payment->Status) ?></td>
                                <td><?= htmlspecialchars($payment->CreatedAt) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-bs-id="<?= $payment->Id ?>"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-id="<?= $payment->Id ?>"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">Không có thanh toán nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit & Delete Modals (tương tự cách store) -->
    <!-- Bạn có thể thêm form POST để update status hoặc xóa payment -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const paymentsData = <?= $paymentsJson ?>;
        // JS để fill modal khi edit/delete giống cách Store
    </script>
</body>

</html>