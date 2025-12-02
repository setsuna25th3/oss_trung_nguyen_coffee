<div class="container content mt-5">
    <h2 class="text-center text-dark fw-bold mb-3">Trang quản trị Cà Phê Trung Nguyên</h2>
    <p class="text-center text-dark lead mb-4">
        Tổng quan hệ thống, số liệu nhanh để quản lý hiệu quả.
    </p>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 mb-3">
        <!-- Khách hàng -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 small-card">
                <div class="card-header text-center text-white fw-bold bg-dark rounded-top">
                    Khách hàng
                </div>
                <div class="card-body">
                    <p class="card-text">Tổng số: <strong>1250</strong> | Mới hôm nay: <strong>12</strong></p>
                    <a href="customer/index.php" class="btn btn-dark w-100 btn-sm">Quản lý khách hàng</a>
                </div>
            </div>
        </div>

        <!-- Nhân viên -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 small-card">
                <div class="card-header text-center text-white fw-bold" style="background:#2a5298;">
                    Nhân viên
                </div>
                <div class="card-body">
                    <p class="card-text">Tổng số: <strong>45</strong> | Online: <strong>10</strong></p>
                    <a href="employ/index.php" class="btn btn-primary w-100 btn-sm">Quản lý nhân viên</a>
                </div>
            </div>
        </div>

        <!-- Sản phẩm -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 small-card">
                <div class="card-header text-center text-white fw-bold" style="background:#11998e;">
                    Sản phẩm
                </div>
                <div class="card-body">
                    <p class="card-text">Tổng số: <strong>320</strong> | Hết hàng: <strong>15</strong></p>
                    <a href="product/index.php" class="btn btn-success w-100 btn-sm">Quản lý sản phẩm</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 mb-3">
        <!-- Danh mục -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 small-card">
                <div class="card-header text-center text-white fw-bold" style="background:#636363;">
                    Danh mục
                </div>
                <div class="card-body">
                    <p class="card-text">Tổng số: <strong>12</strong></p>
                    <a href="catagory/index.php" class="btn btn-secondary w-100 btn-sm">Quản lý danh mục</a>
                </div>
            </div>
        </div>

        <!-- Cửa hàng -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 small-card">
                <div class="card-header text-center text-white fw-bold" style="background:#8e2de2;">
                    Cửa hàng
                </div>
                <div class="card-body">
                    <p class="card-text">Tổng số: <strong>25</strong> | Hoạt động: <strong>23</strong></p>
                    <a href="shop/index.php" class="btn btn-secondary w-100 btn-sm">Quản lý cửa hàng</a>
                </div>
            </div>
        </div>

        <!-- Đơn hàng -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 small-card">
                <div class="card-header text-center text-white fw-bold" style="background:#f7971e;">
                    Đơn hàng
                </div>
                <div class="card-body">
                    <p class="card-text">Mới hôm nay: <strong>23</strong> | Đang xử lý: <strong>12</strong></p>
                    <a href="payment/index.php" class="btn btn-warning w-100 btn-sm">Quản lý đơn hàng</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 mb-3">
        <!-- Đánh giá -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 small-card">
                <div class="card-header text-center text-white fw-bold" style="background:#ff5f6d;">
                    Đánh giá
                </div>
                <div class="card-body">
                    <p class="card-text">Mới: <strong>8</strong> | Chưa duyệt: <strong>3</strong></p>
                    <a href="rate/index.php" class="btn btn-warning w-100 btn-sm">Quản lý đánh giá</a>
                </div>
            </div>
        </div>

        <!-- Doanh thu -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 small-card">
                <div class="card-header text-center text-white fw-bold" style="background:#cb2d3e;">
                    Doanh thu
                </div>
                <div class="card-body">
                    <p class="card-text">Hôm nay: <strong>125 triệu</strong> | Tháng: <strong>3,200 triệu</strong></p>
                    <a href="revenue/index.php" class="btn btn-danger w-100 btn-sm">Quản lý doanh thu</a>
                </div>
            </div>
        </div>

        <!-- Thống kê -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 small-card">
                <div class="card-header text-center text-white fw-bold" style="background:#11998e;">
                    Thống kê
                </div>
                <div class="card-body">
                    <p class="card-text">Xem biểu đồ doanh thu & hóa đơn.</p>
                    <a href="stats/index.php" class="btn btn-success w-100 btn-sm">Xem thống kê</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .content {
        margin-left: 0;
        padding: 20px;
    }

    .card {
        width: 100%;
        max-width: 330px;
        margin: 0 auto;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2);
    }

    .card-body p {
        min-height: 55px;
    }
</style>