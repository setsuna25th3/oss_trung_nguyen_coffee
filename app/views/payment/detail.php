<?php include '../header.php'; ?>

<style>
    /* Cấu trúc flex cho toàn trang */
    body,
    html {
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
    }

    main {
        flex: 1;
        /* Giữ nội dung chính chiếm không gian còn lại */
    }

    .page-header {
        margin-top: 80px;
        padding: 100px 0;
        background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSNRm0D6QVOC7HmWVXXHkgLzAwnrCRs2V8qvw&s');
        background-size: cover;
        background-position: center;
        color: white;
        text-align: center;
        position: relative;
        border-bottom: 5px solid #ffb300;
    }

    .page-header::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 0;
    }

    .page-header h2 {
        position: relative;
        z-index: 1;
        font-size: 52px;
        font-weight: 800;
        letter-spacing: 1px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .invoice-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 40px;
    }

    .invoice-table th {
        background-color: #343a40;
        color: #ffc107;
        width: 30%;
        font-weight: 600;
    }

    .products-table th {
        background-color: #343a40;
        color: #ffc107;
        font-weight: 600;
    }

    .products-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-custom {
        padding: 12px 25px;
        border-radius: 30px;
        background-color: #ffb300;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 600;
        display: inline-block;
        margin-top: 20px;
        /* tạo khoảng cách trên nút */
    }

    .btn-custom:hover {
        background-color: #ff9800;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    img.product-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 28px;
        font-weight: 700;
        color: #343a40;
        margin-bottom: 25px;
        border-bottom: 3px solid #ffb300;
        padding-bottom: 10px;
    }

    @media(max-width:768px) {
        .page-header h2 {
            font-size: 36px;
        }

        img.product-img {
            width: 80px;
            height: 80px;
        }

        .invoice-table th {
            width: 40%;
        }
    }
</style>

<main>
    <div class="page-header">
        <h2>Chi tiết Thanh Toán</h2>
    </div>

    <div class="container my-5">
        <div class="invoice-card">
            <h3 class="section-title">Thông tin hóa đơn</h3>
            <table class="invoice-table table table-bordered">
                <tr>
                    <th>Mã hóa đơn</th>
                    <td><?= $payment['Id'] ?></td>
                </tr>
                <tr>
                    <th>Mã khách hàng</th>
                    <td><?= $payment['CustomerId'] ?></td>
                </tr>
                <tr>
                    <th>Họ</th>
                    <td><?= $payment['FirstName'] ?></td>
                </tr>
                <tr>
                    <th>Tên</th>
                    <td><?= $payment['LastName'] ?></td>
                </tr>
                <tr>
                    <th>Điện thoại</th>
                    <td><?= $payment['Phone'] ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= $payment['Email'] ?></td>
                </tr>
                <tr>
                    <th>Ngày tạo</th>
                    <td><?= date("d/m/Y", strtotime($payment['CreateAt'])) ?></td>
                </tr>
                <tr>
                    <th>Tổng tiền</th>
                    <td class="font-weight-bold"><?= $payment['Total'] ?></td>
                </tr>
            </table>
        </div>

        <div class="invoice-card">
            <h3 class="section-title">Chi tiết sản phẩm</h3>
            <div class="table-responsive">
                <table class="products-table table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Hình ảnh</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Tổng tiền</th>
                            <th>Xem chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payment['PaymentDetail'] as $detail): ?>
                            <tr>
                                <td><?= $detail['ProductName'] ?></td>
                                <td><?php if (!empty($detail['ImageUrl'])): ?><img src="Images/SanPham/<?= $detail['ImageUrl'] ?>" class="product-img"><?php endif; ?></td>
                                <td><?= $detail['Price'] ?></td>
                                <td><?= $detail['Quantity'] ?></td>
                                <td><?= $detail['Total'] ?></td>
                                <td><a href="product_detail.php?id=<?= $detail['ProductId'] ?>" class="btn-custom btn-sm"><i class="fas fa-eye"></i> Xem</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Nút quay lại bên trong card để không chồng footer -->
            <a href="../payment/index.php" class="btn-custom">Quay lại</a>
        </div>
    </div>
</main>

<?php include '../footer.php'; ?>