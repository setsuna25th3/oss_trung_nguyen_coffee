<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['CustomerId'])) {
        header('Location: ../../../views/home/index.php');
        exit();
    }

    require_once __DIR__ . '/../../controllers/CategoryAdminController.php';
    require_once __DIR__ . '/../models/CategoryAdmin.php';
    
    $categoryAdminController = new CategoryAdminController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $content = isset($_POST['content']) ? trim($_POST['content']) : '';

        if (empty($title)) {
            $_SESSION['error_message'] = "Tên danh mục không được để trống.";
            header('Location: index.php');
            exit();
        }

        $newCategory = new CategoryAdmin();
        $newCategory->Title = $title;
        $newCategory->Content = $content;

        $isCreated = $categoryAdminController->createCategory($newCategory);

        if ($isCreated) {
            $_SESSION['success_message'] = "Thêm danh mục thành công!";
        } else {
            $_SESSION['error_message'] = "Thêm danh mục thất bại. Vui lòng thử lại.";
        }

        header('Location: index.php'); 
        exit();
    } else {
        header('Location: index.php'); 
        exit();
    }
?>