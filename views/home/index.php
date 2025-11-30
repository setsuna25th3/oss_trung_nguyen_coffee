<?php
// BẮT BUỘC phải require_once db_connect.php để có session_start() và các hàm hỗ trợ
require_once '../../db_connect.php'; 

// ĐỊNH NGHĨA BASE URL (QUAN TRỌNG: CẦN ĐIỀU CHỈNH NẾU THƯ MỤC GỐC KHÁC)
$base_url = '/oss_trung_nguyen_coffee_nhat_thanh'; 
$image_base = $base_url . '/images/ChiNhanh';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Trung Nguyên Cà Phê</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #fff1e0;
            padding-top: 150px;
            /* tránh bị header che */
            font-family: 'Segoe UI', sans-serif;

            margin: 0;
            padding: 0;
            box-sizing: border-box;

        }

        /* HERO */
        .hero {
            text-align: center;
            padding: 90px 20px 10px;
        }

        .hero h1 {
            font-size: 62px;
            font-weight: 800;
            color: #37474f;
            letter-spacing: 3px;
            margin-bottom: 10px;
        }

        .hero h2 {
            font-size: 26px;
            color: #ffb300;
            font-weight: 600;
            letter-spacing: 4px;
            margin-bottom: 40px;
        }

        .hero p {
            max-width: 960px;
            margin: 0 auto;
            font-size: 16px;
            color: #444;
            line-height: 1.6;
            line-height: 1.8;
        }

        /* SLIDER */
        .banner-slider {
            max-width: 1200px;
            margin: 50px auto;
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            height: 600px;
            margin: 40px auto 100px;
            overflow: hidden;
            border-radius: 18px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .slider-container {
            display: flex;
            height: 100%;
            transition: transform 0.5s ease-in-out;
        }

        .slide {
            min-width: 100%;
            box-sizing: border-box;
            height: 100%;
        }

        .slide img {
            width: 100%;
            height: 500px; /* Chiều cao cố định cho ảnh */
            object-fit: cover; /* Đảm bảo ảnh cover toàn bộ khu vực */
            display: block;
            height: 100%;
            object-fit: cover;
        }

        .prev,
        .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 15px;
            cursor: pointer;
            z-index: 10;
            font-size: 20px;
            border-radius: 50%;
            transition: background 0.3s;
            font-size: 24px;
            border-radius: 50%;
            cursor: pointer;
            transition: 0.3s;
            z-index: 20;
        }

        .prev:hover,
        .next:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        .prev {
            left: 20px;
        }

        .next {
            right: 20px;
        }



        @media (max-width: 768px) {
            .banner-slider {
                height: 300px;
            }

            .hero h1 {
                font-size: 42px;
            }

            .hero h2 {
                font-size: 20px;
            }
        }

    </style>
</head>

<body>

    <?php include '../header.php'; ?>

    <section class="hero">
        <h1>TRUNG NGUYÊN LEGEND</h1>
        <h2>KIẾN TẠO THƯƠNG HIỆU CÀ PHÊ QUỐC GIA</h2>
        <p>
            Hệ thống cửa hàng cà phê Trung Nguyên trải dài trên khắp đất nước và vươn ra quốc tế, mang đến trải nghiệm cà phê chuẩn mực

    <!-- HEADER -->
    <?php
    require_once '../../db_connect.php';
    include "../header.php";
     ?>

    <!-- HERO -->
    <section class="hero">
        <h1>TRUNG NGUYÊN CÀ PHÊ</h1>
        <h2>CÀ PHÊ NĂNG LƯỢNG – CÀ PHÊ ĐỔI ĐỜI</h2>

        <p>
            Trung Nguyên Cà Phê là thương hiệu cà phê hàng đầu Việt Nam, nổi tiếng với sứ mệnh lan tỏa
            văn hóa thưởng thức cà phê Việt đến toàn cầu. Được thành lập vào năm 1996, Trung Nguyên
            cung cấp sản phẩm chất lượng cao với hương vị đậm đà đặc trưng.
            <br><br>
            Chuỗi hệ thống có hơn 1.000 cửa hàng trên toàn quốc, mang đến trải nghiệm cà phê chuẩn mực
            dành cho mọi khách hàng yêu thích văn hóa cà phê Việt.
        </p>
    </section>

    <!-- SLIDER -->
    <div class="banner-slider">
        <button class="prev">&#10094;</button>
        <button class="next">&#10095;</button>

        <div class="slider-container">
            <!-- SỬ DỤNG $image_base CHO ĐƯỜNG DẪN TUYỆT ĐỐI -->
            <div class="slide"><img src="<?= $image_base ?>/chinhanh1.jpg"></div>
            <div class="slide"><img src="<?= $image_base ?>/chinhanh2.jpg"></div>
            <div class="slide"><img src="<?= $image_base ?>/chinhanh3.jpg"></div>
            <div class="slide"><img src="<?= $image_base ?>/chinhanh4.jpg"></div>
            <div class="slide"><img src="<?= $image_base ?>/chinhanh5.jpg"></div>
            <div class="slide"><img src="<?= $image_base ?>/chinhanh6.jpg"></div>
            <div class="slide"><img src="../../images/ChiNhanh/chinhanh1.jpg"></div>
            <div class="slide"><img src="../../images/ChiNhanh/chinhanh2.jpg"></div>
            <div class="slide"><img src="../../images/ChiNhanh/chinhanh3.jpg"></div>
            <div class="slide"><img src="../../images/ChiNhanh/chinhanh4.jpg"></div>
            <div class="slide"><img src="../../images/ChiNhanh/chinhanh5.jpg"></div>
            <div class="slide"><img src="../../images/ChiNhanh/chinhanh6.jpg"></div>
        </div>
    </div>

    <script>
        let index = 0;
        const slides = document.querySelectorAll('.slide');
        const slider = document.querySelector('.slider-container');

        function showSlide(i) {
            index = (i + slides.length) % slides.length;
            slider.style.transform = `translateX(-${index * 100}%)`;
        }

        document.querySelector('.prev').onclick = () => showSlide(index - 1);
        document.querySelector('.next').onclick = () => showSlide(index + 1);

        // Auto slide
        setInterval(() => showSlide(index + 1), 5000);
    </script>

    <?php include '../footer.php'; ?>

        setInterval(() => showSlide(index + 1), 5000);
    </script>

    <!-- FOOTER -->
    <?php include "../footer.php"; ?>


</body>

</html>