<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Trang chủ - Trung Nguyên Cà Phê</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #fff1e0;
            padding-top: 150px;
            font-family: 'Segoe UI', sans-serif;
        }

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
            line-height: 1.8;
        }

        .banner-slider {
            max-width: 1200px;
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
            height: 100%;
        }

        .slide img {
            width: 100%;
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
    <?php include "../header.php"; ?>

    <section class="hero">
        <h1>TRUNG NGUYÊN CÀ PHÊ</h1>
        <h2>CÀ PHÊ NĂNG LƯỢNG - CÀ PHÊ ĐỔI ĐỜI</h2>

        <p>
            Trung Nguyên Cà Phê là thương hiệu cà phê hàng đầu Việt Nam, nổi tiếng với sứ mệnh lan tỏa
            văn hóa thưởng thức cà phê Việt đến toàn cầu. Được thành lập vào năm 1996, Trung Nguyên
            cung cấp sản phẩm chất lượng cao với hương vị đậm đà đặc trưng.
            <br><br>
            Chuỗi hệ thống có hơn 1.000 cửa hàng trên toàn quốc, mang đến trải nghiệm cà phê chuẩn mực
            dành cho mọi khách hàng yêu thích văn hóa cà phê Việt.
        </p>
    </section>

    <div class="banner-slider">
        <button class="prev">&#10094;</button>
        <button class="next">&#10095;</button>

        <div class="slider-container">
            <div class="slide"><img src="../../img/ChiNhanh/chinhanh1.jpg"></div>
            <div class="slide"><img src="../../img/ChiNhanh/chinhanh2.jpg"></div>
            <div class="slide"><img src="../../img/ChiNhanh/chinhanh3.jpg"></div>
            <div class="slide"><img src="../../img/ChiNhanh/chinhanh4.jpg"></div>
            <div class="slide"><img src="../../img/ChiNhanh/chinhanh5.jpg"></div>
            <div class="slide"><img src="../../img/ChiNhanh/chinhanh6.jpg"></div>
        </div>
    </div>

    <script>
        const slider = document.querySelector('.slider-container');
        const slides = document.querySelectorAll('.slide');
        const prevBtn = document.querySelector('.prev');
        const nextBtn = document.querySelector('.next');

        const originalTotal = slides.length;

        // Clone first và last
        const firstClone = slides[0].cloneNode(true);
        const lastClone = slides[originalTotal - 1].cloneNode(true);

        slider.appendChild(firstClone);
        slider.insertBefore(lastClone, slider.firstChild);

        // Lấy lại danh sách slide sau khi clone
        const updatedSlides = document.querySelectorAll('.slide');
        const totalSlides = updatedSlides.length;

        let currentIndex = 1; // bắt đầu từ slide gốc đầu tiên
        slider.style.transform = `translateX(-${currentIndex * 100}%)`;

        let interval = setInterval(() => moveSlide(1), 5000);

        function moveSlide(direction) {
            currentIndex += direction;
            slider.style.transition = 'transform 0.5s ease-in-out';
            slider.style.transform = `translateX(-${currentIndex * 100}%)`;
        }

        // Khi chuyển slide xong, reset position nếu cần
        slider.addEventListener('transitionend', () => {
            if (currentIndex === 0) { // nhảy từ clone cuối về slide cuối
                slider.style.transition = 'none';
                currentIndex = originalTotal;
                slider.style.transform = `translateX(-${currentIndex * 100}%)`;
            } else if (currentIndex === totalSlides - 1) { // nhảy từ clone đầu về slide đầu
                slider.style.transition = 'none';
                currentIndex = 1;
                slider.style.transform = `translateX(-${currentIndex * 100}%)`;
            }
        });

        prevBtn.addEventListener('click', () => {
            moveSlide(-1);
            resetInterval();
        });
        nextBtn.addEventListener('click', () => {
            moveSlide(1);
            resetInterval();
        });

        function resetInterval() {
            clearInterval(interval);
            interval = setInterval(() => moveSlide(1), 5000);
        }
    </script>


    <?php include "../footer.php"; ?>

</body>

</html>