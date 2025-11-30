<?php
ob_start();
// Đảm bảo session_start() được gọi (thường đã có trong db_connect.php)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Lấy thông tin người dùng từ session
$isLoggedIn = isset($_SESSION['customer_id']);
// Lấy Tên đầy đủ được lưu trong sign_in_process.php
$fullName = $_SESSION['customer_fullname'] ?? 'Khách hàng'; 
$profileLink = '../customer/profile.php';
// Đảm bảo đường dẫn đến sign_out.php là đúng
$logoutLink = '../customer/sign_out.php';    
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        
        padding-top: 160px; 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .top-bar {
        background-color: #37474f;
        color: white;
        padding: 20px 60px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        border-radius: 0 0 30px 30px;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
    }

    .top-bar .left {
        display: flex;
        gap: 25px;
    }

    .top-bar .left span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .top-bar .right a {
        color: white;
        text-decoration: none;
        padding: 5px 12px;
        background-color: #455a64;
        border-radius: 25px;
        font-weight: 500;
        /* Thêm margin để tách biệt các nút (Xin chào/Đăng xuất) */
        margin-left: 8px; 
    }

    .top-bar .right a:hover {
        background-color: #546e7a;
    }
    
    /* Thiết lập cho liên kết "Xin chào" không có background */
    .top-bar .right a.welcome-link {
        background-color: transparent;
        padding: 5px 0; /* Giảm padding để trông giống chữ hơn */
        border-radius: 0;
        font-weight: 600;
        text-decoration: none;
    }
    .top-bar .right a.welcome-link:hover {
        background-color: transparent;
        text-decoration: underline;
    }


    .menu {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 80px;
        background-color: white;
        position: fixed;
        top: 59px;
        left: 0;
        width: 100%;
        z-index: 999;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .menu-nav a {
        margin-right: 35px;
        text-decoration: none;
        color: #37474f;
        font-weight: 600;
        font-size: 20px;
        transition: color 0.3s, transform 0.3s;
        display: inline-block;
    }

    .menu-nav a:hover {
        color: #ffb300;
        transform: scale(1.1);
    }

    .menu-nav a.dropdown::after {
        content: " ▼";
        font-size: 10px;
    }

    .logo img {
        height: 65px;
    }

    .menu-icons {
        display: flex;
        align-items: center;
        gap: 22px;
        font-size: 23px;
        color: #37474f;
    }

    .menu-icons a {
        color: #333;
        text-decoration: none;
        font-size: 20px;
    }

    .menu-icons a:hover {
        color: brown;
    }


    .menu-icons .cart {
        position: relative;
    }

    .menu-icons .cart sup {
        position: absolute;
        top: -8px;
        right: -10px;
        background: #ffb300;
        color: white;
        font-size: 12px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    @media(max-width:992px) {
        .top-bar {
            padding: 10px 30px;
            border-radius: 0 0 20px 20px;
            font-size: 12px;
        }

        .menu {
            padding: 20px 40px;
            flex-direction: column;
            gap: 20px;
        }

        .menu-nav a {
            font-size: 18px;
            margin-right: 20px;
        }
    }

    @media(max-width:576px) {
        .menu-nav a {
            font-size: 16px;
            margin-right: 15px;
        }

        .logo img {
            height: 50px;
        }
    }

    .dropdown-menu {
        position: relative;
        display: inline-block;
        padding-bottom: 10px;
    }

    .dropdown-btn {
        cursor: pointer;
        font-weight: 600;
        font-size: 20px;
        color: #37474f;
        display: inline-block;
    }

    .dropdown-btn:hover {
        color: #ffb300;
    }

    .dropdown-list {
        list-style: none;
        position: absolute;
        top: 28px;
        left: 0;
        background: white;
        padding: 12px 0;
        width: 220px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: none;
        z-index: 9999;
    }

    .dropdown-menu:hover .dropdown-list {
        display: block;
    }

    .dropdown-list li a {
        display: block;
        padding: 10px 18px;
        font-size: 17px;
        color: #37474f;
        text-decoration: none;
    }

    .dropdown-list li a:hover {
        background-color: #ffb300;
        color: white;
    }

    .dropdown-menu,
    .dropdown-menu a,
    .dropdown-menu div,
    .dropdown-btn {
        border: none !important;
        box-shadow: none !important;
    }
</style>

<header>
    <div class="top-bar">
        <div class="left">
            <span><i class="fas fa-map-marker-alt"></i> Trung Nguyên Coffee</span>
            <span><i class="fas fa-envelope"></i> contact@trungnguyencoffee.com</span>
        </div>
        
        <div class="right">
            <?php 
            // KHÁCH HÀNG ĐÃ ĐĂNG NHẬP
            if (isset($_SESSION['customer_id'])): 
                // Lấy tên đầy đủ của khách hàng (đã lưu trong sign_in_process.php)
                $fullName = htmlspecialchars($_SESSION['customer_fullname'] ?? 'Khách hàng');
                // Đường dẫn tương đối từ 'views' (thư mục chứa header.php)
                $profileLink = '../customer/profile.php'; 
                $logoutLink = '../../customer/sign_out.php';    
            ?>
                <a href="<?= $profileLink ?>" class="welcome-link">Xin chào, **<?= $fullName ?>**</a>
                <a href="<?= $logoutLink = '../customer/sign_out.php'; ?>">Đăng xuất</a>
            <?php else: ?>
                <a href="../customer/login.php">Đăng nhập</a> / <a href="../customer/signup.php">Đăng ký</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="menu">
        <div class="logo">
            <img src="../../images/logo/logo.png" alt="Trung Nguyên Legend">
        </div>
        <div class="menu-nav">
            <a href="../home/index.php">Trang chủ</a>
            <a href="../product/index.php">Cửa hàng</a>

            <div class="dropdown-menu">
                <a class="dropdown-btn">Danh mục ▼</a>

                <ul class="dropdown-list">
                    </ul>
            </div>

            <a href="../contact/index.php">Liên hệ</a>
        </div>

        <div class="menu-icons">
            <a href="../contact/index.php"><i class="fas fa-phone-alt"></i></a>
            <a href="../cart/index.php" class="cart">
                <i class="fas fa-shopping-cart"></i><sup>0</sup>
            </a>
            
            <?php if (isset($_SESSION['customer_id'])): ?>
                <a href="../customer/profile.php"><i class="fas fa-user"></i></a>
            <?php else: ?>
                <a href="../customer/login.php"><i class="fas fa-user"></i></a>
            <?php endif; ?>
        </div>

    </div>
</header>