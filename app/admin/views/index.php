<?php
    session_start();
    require_once '../../controllers/CustomerController.php';
    $customerController = new CustomerController();
    if($customerController->getCustomerById($_SESSION['CustomerId'])->Role){
        $adminId = $_SESSION['CustomerId'];
        $adminName = $_SESSION['CustomerName']; 
        $title = "Trang Quản Trị - Trung Nguyên Coffee";
        $page = $_GET['page'] ?? 'dashboard';
        $allowed_pages = ['dashboard', 'category', 'product', 'store', 'employ', 'role', 'customer', 'payment', 'revenue', 'review', 'stats'];
        if (!in_array($page, $allowed_pages)) {
            $page = 'dashboard';
        }
    }
    else{
        header('Location: ../../views/home/index.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .vertical-navbar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background: #333;
            padding-top: 20px;
            transition: width 0.3s;
            overflow: hidden;
            z-index: 1000;
            overflow-y: auto;
        }

        .vertical-navbar.collapsed {
            width: 70px;
        }

        .vertical-navbar a {
            padding: 14px 20px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #444;
        }

        .vertical-navbar a i {
            margin-right: 10px;
            width: 25px;
            text-align: center;
        }

        .vertical-navbar a:hover {
            background: #575757;
        }

        .navbar-logo {
            max-width: 80%;
            height: auto;
            margin-bottom: 10px;
        }

        .navbar-header {
            color: #ffc107;
            text-align: center;
        }

        .vertical-navbar.collapsed a span,
        .vertical-navbar.collapsed .navbar-title {
            display: none;
        }

        /* Container content */
        .container-wrapper {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s;
        }

        .container-wrapper.collapsed {
            margin-left: 70px;
        }

        /* Table style */
        .table-wrapper {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background: #343a40;
            color: #fff;
        }

        .table-hover tbody tr:hover {
            background: #f1f1f1;
        }

        .btn i {
            margin-right: 5px;
        }

        .btn-add {
            margin-bottom: 15px;
        }

        .navbar-toggler {
            display: block;
            width: 40px;
            height: 40px;
            border-radius: 5px;
            color: wheat;
        }

        @media (max-width: 991.98px) {
            .vertical-navbar {
                width: 70px;
            }

            .vertical-navbar a span,
            .vertical-navbar .navbar-title {
                display: none;
            }

            .container-wrapper {
                margin-left: 70px;
            }
        }
    </style>
</head>

<body>
    <nav class="vertical-navbar">
        <button class="btn btn-dark navbar-toggler"><i class="fas fa-bars"></i></button>

        <div class="navbar-header mb-3">
            <img src="../../img/Logo/logo.jpg" alt="Logo" class="navbar-logo">
            <div class="navbar-title fw-bolder">Xin chào, <?php echo $adminName ?></div>
        </div>
        <a href="../../views/home/index.php" class="no-ajax"><i class="fas fa-arrow-left"></i></i><span> Trang khách</span></a>
        <a href="?page=dashboard"><i class="fas fa-home"></i><span> Trang quản trị</span></a>
        <a href="?page=category"><i class="fas fa-list-alt"></i><span> Quản lý danh mục</span></a>
        <a href="?page=product"><i class="fas fa-glass-martini-alt"></i><span> Quản lý sản phẩm</span></a>
        <a href="?page=store"><i class="fas fa-store"></i><span> Quản lý cửa hàng</span></a>
        <a href="?page=employee"><i class="fas fa-users"></i><span> Quản lý nhân viên</span></a>
        <a href="?page=role"><i class="fas fa-user-tag"></i></i><span> Quản lý chức vụ</span></a>
        <a href="?page=customer"><i class="fas fa-user-tie"></i><span> Quản lý khách hàng</span></a>
        <a href="?page=payment"><i class="fas fa-file-invoice"></i><span> Quản lý hóa đơn</span></a>
        <a href="?page=revenue"><i class="fas fa-chart-line"></i><span> Quản lý doanh thu</span></a>
        <a href="?page=review"><i class="fas fa-star"></i><span> Quản lý đánh giá</span></a>
        <a href="?page=stats"><i class="fas fa-chart-bar"></i><span> Thống kê</span></a>
    </nav>

    <div class="container-wrapper">
        <main role="main" class="pb-3" id="main-content">
            <?php include $page . '/index.php'; ?>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const navbar = document.querySelector('.vertical-navbar');
        const container = document.querySelector('.container-wrapper');
        const toggleBtn = document.querySelector('.navbar-toggler');

        toggleBtn.addEventListener('click', () => {
            navbar.classList.toggle('collapsed');
            container.classList.toggle('collapsed');
        });

        function checkWindowSize() {
            if (window.innerWidth < 992) {
                navbar.classList.add('collapsed');
                container.classList.add('collapsed');
            } else {
                navbar.classList.remove('collapsed');
                container.classList.remove('collapsed');
            }
        }

        window.addEventListener('resize', checkWindowSize);
        checkWindowSize();

        // --------- AJAX Load Page ---------
        function loadPage(page) {
            $.ajax({
                url: page + '/index.php',
                method: 'GET',
                success: function(data) {
                    $('#main-content').html(data);
                },
                error: function() {
                    $('#main-content').html('<p class="text-danger">Không thể load dữ liệu.</p>');
                }
            });
        }

        // Click menu
        $('.vertical-navbar a').click(function(e) {
            if ($(this).hasClass('no-ajax')) {
                // cho phép link load bình thường
                return;
            }

            e.preventDefault();
            let url = $(this).attr('href');
            let page = url.split('=')[1];

            // Load nội dung
            loadPage(page);

            // Cập nhật URL mà không reload trang
            history.pushState({
                page: page
            }, '', '?page=' + page);
        });
    </script>
</body>

</html>