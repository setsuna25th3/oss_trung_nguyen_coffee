-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2025 at 09:01 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cafe_trungnguyen`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `Id` int(11) NOT NULL,
  `CustomerId` int(11) NOT NULL,
  `ProductId` int(11) NOT NULL,
  `StoreId` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `Id` int(11) NOT NULL,
  `Title` varchar(100) NOT NULL,
  `Content` varchar(500) DEFAULT NULL,
  `CreateAt` datetime DEFAULT current_timestamp(),
  `UpdateAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`Id`, `Title`, `Content`, `CreateAt`, `UpdateAt`) VALUES
(1, 'Cà phê', 'Thưởng thức hương vị đậm đà...', '2025-11-17 14:52:54', '2025-11-17 14:52:54'),
(2, 'Trà', 'Thanh lọc cơ thể và xoa dịu tâm hồn...', '2025-11-17 14:52:54', '2025-11-17 14:52:54'),
(3, 'Nước ép', 'Ngọt lành và tươi mát từ trái cây...', '2025-11-17 14:52:54', '2025-11-17 14:52:54'),
(4, 'Sinh tố', 'Những ly sinh tố mịn màng...', '2025-11-17 14:52:54', '2025-11-17 14:52:54'),
(5, 'Đồ ăn nhẹ', 'Những món ăn nhẹ đầy hấp dẫn...', '2025-11-17 14:52:54', '2025-11-17 14:52:54'),
(6, 'Các món khác', 'Từ nước lọc, sữa tươi...', '2025-11-17 14:52:54', '2025-11-17 14:52:54');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Id` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `Address` varchar(300) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Email` varchar(50) NOT NULL,
  `Img` varchar(300) DEFAULT NULL,
  `RegisteredAt` datetime DEFAULT current_timestamp(),
  `UpdateAt` datetime DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `Password` varchar(200) NOT NULL,
  `RandomKey` varchar(100) DEFAULT NULL,
  `IsActive` tinyint(1) DEFAULT 1,
  `Role` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `Id` int(11) NOT NULL,
  `FullName` varchar(100) NOT NULL,
  `StoreId` int(11) NOT NULL,
  `RoleId` int(11) NOT NULL,
  `Salary` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`Id`, `FullName`, `StoreId`, `RoleId`, `Salary`) VALUES
(1, 'Nguyễn Văn Nam', 1, 1, 7000000.00),
(2, 'Trần Thị Lan', 1, 2, 6500000.00),
(3, 'Lê Minh Tuấn', 1, 3, 6000000.00),
(4, 'Phạm Thị Hồng', 1, 4, 12000000.00),
(5, 'Hoàng Văn Sơn', 1, 5, 5500000.00),
(6, 'Vũ Thị Mai', 2, 1, 7000000.00),
(7, 'Đinh Văn Hoàng', 2, 2, 6500000.00),
(8, 'Ngô Thị Thanh', 2, 3, 6000000.00),
(9, 'Bùi Văn Quang', 2, 4, 12000000.00),
(10, 'Phan Thị Trang', 2, 5, 5500000.00),
(11, 'Nguyễn Thị Nhung', 3, 1, 7000000.00),
(12, 'Trần Văn Dũng', 3, 2, 6500000.00),
(13, 'Lê Thị Bích', 3, 3, 6000000.00),
(14, 'Phạm Văn Tài', 3, 4, 12000000.00),
(15, 'Hoàng Thị Hạnh', 3, 5, 5500000.00),
(16, 'Vũ Văn Phúc', 4, 1, 7000000.00),
(17, 'Đinh Thị Oanh', 4, 2, 6500000.00),
(18, 'Ngô Văn Kiên', 4, 3, 6000000.00),
(19, 'Bùi Thị Lan', 4, 4, 12000000.00),
(20, 'Phan Văn Cường', 4, 5, 5500000.00),
(21, 'Nguyễn Văn Hùng', 5, 1, 7000000.00),
(22, 'Trần Thị Dung', 5, 2, 6500000.00),
(23, 'Lê Văn Thành', 5, 3, 6000000.00),
(24, 'Phạm Thị Thu', 5, 4, 12000000.00),
(25, 'Hoàng Văn Long', 5, 5, 5500000.00);

-- --------------------------------------------------------

--
-- Table structure for table `employeerole`
--

CREATE TABLE `employeerole` (
  `Id` int(11) NOT NULL,
  `RoleName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employeerole`
--

INSERT INTO `employeerole` (`Id`, `RoleName`) VALUES
(1, 'Pha chế'),
(2, 'Thu ngân'),
(3, 'Phục vụ'),
(4, 'Quản lý'),
(5, 'Dọn dẹp');

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE `page` (
  `Id` int(11) NOT NULL,
  `ParentId` int(11) DEFAULT NULL,
  `Title` varchar(100) NOT NULL,
  `PageIndex` int(11) DEFAULT NULL,
  `IsVisible` tinyint(1) DEFAULT 1,
  `Url` varchar(300) DEFAULT NULL,
  `StoreId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `Id` int(11) NOT NULL,
  `CustomerId` int(11) NOT NULL,
  `StoreId` int(11) NOT NULL,
  `Total` decimal(18,2) NOT NULL,
  `Carrier` varchar(100) DEFAULT NULL,
  `TrackingCode` varchar(100) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paymentdetail`
--

CREATE TABLE `paymentdetail` (
  `Id` int(11) NOT NULL,
  `PaymentId` int(11) NOT NULL,
  `ProductId` int(11) NOT NULL,
  `Price` decimal(18,2) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `Id` int(11) NOT NULL,
  `Title` varchar(100) NOT NULL,
  `Content` varchar(500) DEFAULT NULL,
  `Img` varchar(300) DEFAULT NULL,
  `Price` decimal(18,2) NOT NULL,
  `Rate` decimal(3,2) DEFAULT NULL,
  `CreateAt` datetime DEFAULT current_timestamp(),
  `UpdateAt` datetime DEFAULT NULL,
  `CategoryId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`Id`, `Title`, `Content`, `Img`, `Price`, `Rate`, `CreateAt`, `UpdateAt`, `CategoryId`) VALUES
(1, 'Cà phê sữa đá', 'Một ly cà phê sữa đá đậm đà và thơm ngon, pha chế từ cà phê nguyên chất cùng sữa đặc, thích hợp cho những ngày làm việc bận rộn.', 'Hình-App_3006021-Cà-Phê-Sữa.jpg', 25000.00, 4.50, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 1),
(2, 'Trà đào cam sả', 'Trà đào cam sả thanh mát, mang đến sự kết hợp hài hòa giữa vị ngọt của đào, vị thanh của cam và mùi thơm nhẹ từ sả.', '30.-Tra-Dao-Cam-Sa.png', 30000.00, 4.50, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 2),
(3, 'Nước ép cam', 'Nước ép cam nguyên chất, cung cấp vitamin C dồi dào, giúp tăng cường sức đề kháng và giải khát tuyệt vời.', 'camvat.jpg', 25000.00, 4.00, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 3),
(4, 'Bánh mì nướng bơ', 'Bánh mì giòn tan, thấm đẫm bơ tỏi thơm lừng, một món ăn nhẹ lý tưởng để nhâm nhi cùng cà phê hoặc trà.', 'lam-banh-mi-nuong-bo-toi.jpg', 25000.00, 4.70, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 5),
(5, 'Bánh flan', 'Món bánh flan mềm mịn, ngọt nhẹ và thơm mùi caramel, là lựa chọn hoàn hảo cho những bữa ăn nhẹ hoặc tráng miệng.', 'Flan-trung.jpg', 10000.00, 4.60, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 5),
(6, 'Cà phê đen nóng', 'Một ly cà phê đen nóng đậm vị, giữ trọn hương thơm nguyên bản, dành cho những ai yêu thích sự đơn giản mà tinh tế.', 'images-_1_ (1).jpg', 20000.00, 4.40, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 1),
(7, 'Trà sữa trân châu đường đen', 'Trà sữa trân châu đường đen với vị béo ngọt hòa quyện, trân châu dai giòn, hương vị hấp dẫn không thể bỏ qua.', 'mua-nguyen-lieu-lam-tra-sua-tran-chau-duong-den-o-dau_20240527014654.jpg', 25000.00, 4.50, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 2),
(8, 'Nước ép dưa hấu', 'Nước ép dưa hấu tươi ngon, ngọt mát và giải nhiệt cho ngày hè oi bức.', 'Hình-App_3006021-Ép-Dưa-Hấu.jpg', 25000.00, 4.00, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 3),
(9, 'Sinh tố xoài', 'Sinh tố xoài ngọt lịm, đậm vị xoài chín.', 'sinh-to-xoai.jpg', 35000.00, 4.00, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 4),
(10, 'Bánh ngọt choco', 'Bánh ngọt chocolate mềm mịn, thơm ngon.', 'images-_2_ (1).jpg', 27000.00, 4.70, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 5),
(11, 'Espresso', 'Espresso đậm vị, phù hợp cho người yêu thích cà phê mạnh.', 'espresso-la-gi.jpg', 28000.00, 4.50, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 1),
(12, 'Trà gừng', 'Trà gừng nóng ấm, tốt cho sức khỏe.', 'Trung-Nguyen_Tet1188-1x1-1-800x800.jpg', 30000.00, 4.50, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 2),
(13, 'Nước ép táo', 'Nước ép táo ngọt ngào, tốt cho sức khỏe và sắc đẹp.', 'cong-dung-cua-nuoc-ep-tao-va-thoi-diem-uong-nuoc-ep-tao-tot-nhat-nuoc_tao_1-1596696321-81-width800height500.jpg', 30000.00, 4.00, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 3),
(14, 'Sinh tố dâu tây', 'Sinh tố dâu tây chua ngọt, thơm ngon và bổ dưỡng.', 'sinh-to-dau-tay.jpg', 40000.00, 4.50, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 4),
(15, 'Snack khoai tây', 'Snack khoai tây giòn rụm, thích hợp cho các buổi trò chuyện.', 'orion-vi-ga-teriyaki-osaka-goi-lon-va-goi-nho-banh-keo-an-vat-imnuts-1_9ca3d29e67c34d26bc27b89f219abfde_master.jpg', 10000.00, 4.60, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 5),
(16, 'Sữa chua', 'Bổ sung dưỡng chất cho sức khỏe, làm da sáng trẻ đẹp', 'sua_chua_an_vinamilk_it_duong__hop_100gr__d4904f4a20c5499f8d7670b8c0ff0094.jpg', 20000.00, 4.00, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 6),
(17, 'Latte', 'Latte sữa béo, vị nhẹ nhàng dễ uống, phù hợp cho mọi người.', '24.-Picasso-Latte-2-_-No.png', 30000.00, 5.00, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 3),
(18, 'Cappuccino', 'Cappuccino đậm đà, thơm ngon với lớp bọt sữa mịn màng.', 'istockphoto-505168330-612x612.jpg', 30000.00, 5.00, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 1),
(19, 'Cà phê đá', 'Cà phê đá nguyên chất, thơm ngon, phù hợp cho những ai yêu thích vị cà phê đậm đà.', 'pngtree-iced-coffee-png-image_9237463.png', 20000.00, 5.00, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 1),
(20, 'Cà phê sữa', 'Cà phê sữa ngọt dịu, kết hợp giữa vị cà phê và sữa đặc truyền thống.', 'images.jpg', 25000.00, 5.00, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 1),
(21, 'Cà phê dừa', 'Cà phê kết hợp với nước cốt dừa béo ngậy, độc đáo và thơm ngon.', 'images-_2_.jpg', 25000.00, 4.00, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 1),
(22, 'Cà phê trứng', 'Sở hữu kiểu dáng mạnh mẽ, gam màu sang trọng, âm thanh sống động, hiệu ứng đèn LED RGB đẹp mắt, kết nối không dây nhanh chóng mang đến cho bạn những trải nghiệm tuyệt vời.', 'images-_1_.jpg', 25000.00, 4.00, '2025-11-17 14:54:38', '2025-11-17 14:54:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `productreview`
--

CREATE TABLE `productreview` (
  `Id` int(11) NOT NULL,
  `ProductId` int(11) NOT NULL,
  `CustomerId` int(11) NOT NULL,
  `Rating` int(11) DEFAULT NULL,
  `Comment` varchar(500) DEFAULT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `revenue`
--

CREATE TABLE `revenue` (
  `Id` int(11) NOT NULL,
  `StoreId` int(11) NOT NULL,
  `Month` int(11) DEFAULT NULL,
  `Year` int(11) DEFAULT NULL,
  `Total` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipment`
--

CREATE TABLE `shipment` (
  `Id` int(11) NOT NULL,
  `PaymentId` int(11) NOT NULL,
  `Carrier` varchar(100) DEFAULT NULL,
  `TrackingCode` varchar(100) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `Latitude` decimal(9,6) DEFAULT NULL,
  `Longitude` decimal(9,6) DEFAULT NULL,
  `UpdatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `Id` int(11) NOT NULL,
  `StoreName` varchar(200) NOT NULL,
  `Address` varchar(300) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `OpenTime` varchar(20) DEFAULT NULL,
  `CloseTime` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`Id`, `StoreName`, `Address`, `Phone`, `OpenTime`, `CloseTime`) VALUES
(1, 'Trung Nguyên Legend – Võ Trứ', '148 Võ Trứ, Phường Tân Lập, Nha Trang, Khánh Hòa', '0258 3516 279', '06:30', '21:30'),
(2, 'Trung Nguyên Legend – Lê Thánh Tôn', '44‑46 Lê Thánh Tôn, Phường Lộc Thọ, Nha Trang, Khánh Hòa', '0918 572 620', '06:30', '22:00'),
(3, 'Trung Nguyên Legend – Vĩnh Phước', 'Vĩnh Phước, Nha Trang, Khánh Hòa', '0911 622 947', '06:30', '22:00'),
(4, 'Trung Nguyên Legend – 19/5', '7549+RJG Đường 19/5, Vĩnh Điềm Trung, Nha Trang, Khánh Hòa', '0978 099 788', '07:00', '21:30'),
(5, 'Trung Nguyên E‑Coffee – Lý Tự Trọng', '38 Lý Tự Trọng, Phường Lộc Thọ, Nha Trang, Khánh Hòa', '088.673.1188', '07:00', '21:00');

-- --------------------------------------------------------

--
-- Table structure for table `storeproduct`
--

CREATE TABLE `storeproduct` (
  `Id` int(11) NOT NULL,
  `StoreId` int(11) NOT NULL,
  `ProductId` int(11) NOT NULL,
  `IsAvailable` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `storeproduct`
--

INSERT INTO `storeproduct` (`Id`, `StoreId`, `ProductId`, `IsAvailable`) VALUES
(24, 1, 1, 0),
(25, 1, 2, 1),
(26, 1, 3, 1),
(27, 1, 4, 0),
(28, 1, 5, 1),
(29, 1, 6, 0),
(30, 1, 7, 1),
(31, 1, 8, 1),
(32, 1, 9, 1),
(33, 1, 10, 1),
(34, 1, 11, 0),
(35, 1, 12, 1),
(36, 1, 13, 1),
(37, 1, 14, 1),
(38, 1, 15, 1),
(39, 1, 16, 1),
(40, 1, 17, 1),
(41, 1, 18, 0),
(42, 1, 19, 1),
(43, 1, 20, 0),
(44, 1, 21, 0),
(45, 1, 22, 1),
(46, 2, 1, 1),
(47, 2, 2, 1),
(48, 2, 3, 0),
(49, 2, 4, 1),
(50, 2, 5, 1),
(51, 2, 6, 0),
(52, 2, 7, 1),
(53, 2, 8, 1),
(54, 2, 9, 1),
(55, 2, 10, 1),
(56, 2, 11, 0),
(57, 2, 12, 1),
(58, 2, 13, 0),
(59, 2, 14, 1),
(60, 2, 15, 1),
(61, 2, 16, 1),
(62, 2, 17, 1),
(63, 2, 18, 1),
(64, 2, 19, 0),
(65, 2, 20, 1),
(66, 2, 21, 1),
(67, 2, 22, 1),
(68, 3, 1, 1),
(69, 3, 2, 1),
(70, 3, 3, 0),
(71, 3, 4, 1),
(72, 3, 5, 1),
(73, 3, 6, 1),
(74, 3, 7, 1),
(75, 3, 8, 1),
(76, 3, 9, 1),
(77, 3, 10, 1),
(78, 3, 11, 0),
(79, 3, 12, 1),
(80, 3, 13, 1),
(81, 3, 14, 1),
(82, 3, 15, 0),
(83, 3, 16, 1),
(84, 3, 17, 1),
(85, 3, 18, 1),
(86, 3, 19, 1),
(87, 3, 20, 0),
(88, 3, 21, 1),
(89, 3, 22, 1),
(90, 4, 1, 1),
(91, 4, 2, 1),
(92, 4, 3, 0),
(93, 4, 4, 1),
(94, 4, 5, 1),
(95, 4, 6, 0),
(96, 4, 7, 1),
(97, 4, 8, 1),
(98, 4, 9, 1),
(99, 4, 10, 1),
(100, 4, 11, 1),
(101, 4, 12, 1),
(102, 4, 13, 1),
(103, 4, 14, 1),
(104, 4, 15, 1),
(105, 4, 16, 1),
(106, 4, 17, 1),
(107, 4, 18, 0),
(108, 4, 19, 1),
(109, 4, 20, 1),
(110, 4, 21, 0),
(111, 4, 22, 1),
(112, 5, 1, 1),
(113, 5, 2, 1),
(114, 5, 3, 1),
(115, 5, 4, 1),
(116, 5, 5, 1),
(117, 5, 6, 1),
(118, 5, 7, 1),
(119, 5, 8, 1),
(120, 5, 9, 0),
(121, 5, 10, 1),
(122, 5, 11, 0),
(123, 5, 12, 0),
(124, 5, 13, 1),
(125, 5, 14, 0),
(126, 5, 15, 1),
(127, 5, 16, 1),
(128, 5, 17, 1),
(129, 5, 18, 1),
(130, 5, 19, 1),
(131, 5, 20, 1),
(132, 5, 21, 0),
(133, 5, 22, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `CustomerId` (`CustomerId`),
  ADD KEY `ProductId` (`ProductId`),
  ADD KEY `StoreId` (`StoreId`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `StoreId` (`StoreId`),
  ADD KEY `RoleId` (`RoleId`);

--
-- Indexes for table `employeerole`
--
ALTER TABLE `employeerole`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `StoreId` (`StoreId`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `CustomerId` (`CustomerId`),
  ADD KEY `StoreId` (`StoreId`);

--
-- Indexes for table `paymentdetail`
--
ALTER TABLE `paymentdetail`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `PaymentId` (`PaymentId`),
  ADD KEY `ProductId` (`ProductId`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `CategoryId` (`CategoryId`);

--
-- Indexes for table `productreview`
--
ALTER TABLE `productreview`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `ProductId` (`ProductId`,`CustomerId`),
  ADD KEY `CustomerId` (`CustomerId`);

--
-- Indexes for table `revenue`
--
ALTER TABLE `revenue`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `StoreId` (`StoreId`);

--
-- Indexes for table `shipment`
--
ALTER TABLE `shipment`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `PaymentId` (`PaymentId`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `storeproduct`
--
ALTER TABLE `storeproduct`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `StoreId` (`StoreId`),
  ADD KEY `ProductId` (`ProductId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `employeerole`
--
ALTER TABLE `employeerole`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `page`
--
ALTER TABLE `page`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paymentdetail`
--
ALTER TABLE `paymentdetail`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `productreview`
--
ALTER TABLE `productreview`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `revenue`
--
ALTER TABLE `revenue`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipment`
--
ALTER TABLE `shipment`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `storeproduct`
--
ALTER TABLE `storeproduct`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`CustomerId`) REFERENCES `customer` (`Id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`ProductId`) REFERENCES `product` (`Id`),
  ADD CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`StoreId`) REFERENCES `store` (`Id`);

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`StoreId`) REFERENCES `store` (`Id`),
  ADD CONSTRAINT `employee_ibfk_2` FOREIGN KEY (`RoleId`) REFERENCES `employeerole` (`Id`);

--
-- Constraints for table `page`
--
ALTER TABLE `page`
  ADD CONSTRAINT `page_ibfk_1` FOREIGN KEY (`StoreId`) REFERENCES `store` (`Id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`CustomerId`) REFERENCES `customer` (`Id`),
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`StoreId`) REFERENCES `store` (`Id`);

--
-- Constraints for table `paymentdetail`
--
ALTER TABLE `paymentdetail`
  ADD CONSTRAINT `paymentdetail_ibfk_1` FOREIGN KEY (`PaymentId`) REFERENCES `payment` (`Id`),
  ADD CONSTRAINT `paymentdetail_ibfk_2` FOREIGN KEY (`ProductId`) REFERENCES `product` (`Id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`CategoryId`) REFERENCES `category` (`Id`);

--
-- Constraints for table `productreview`
--
ALTER TABLE `productreview`
  ADD CONSTRAINT `productreview_ibfk_1` FOREIGN KEY (`ProductId`) REFERENCES `product` (`Id`),
  ADD CONSTRAINT `productreview_ibfk_2` FOREIGN KEY (`CustomerId`) REFERENCES `customer` (`Id`);

--
-- Constraints for table `revenue`
--
ALTER TABLE `revenue`
  ADD CONSTRAINT `revenue_ibfk_1` FOREIGN KEY (`StoreId`) REFERENCES `store` (`Id`);

--
-- Constraints for table `shipment`
--
ALTER TABLE `shipment`
  ADD CONSTRAINT `shipment_ibfk_1` FOREIGN KEY (`PaymentId`) REFERENCES `payment` (`Id`);

--
-- Constraints for table `storeproduct`
--
ALTER TABLE `storeproduct`
  ADD CONSTRAINT `storeproduct_ibfk_1` FOREIGN KEY (`StoreId`) REFERENCES `store` (`Id`),
  ADD CONSTRAINT `storeproduct_ibfk_2` FOREIGN KEY (`ProductId`) REFERENCES `product` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
