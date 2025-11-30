<?php
/**
 * Cấu hình gửi mail cho chức năng quên mật khẩu.
 * Thay đổi các giá trị bên dưới cho phù hợp với máy chủ SMTP bạn đang dùng.
 * Có thể đặt biến môi trường để ghi đè (ví dụ: MAIL_HOST, MAIL_USERNAME,...).
 */
return [
    'host' => getenv('MAIL_HOST') ?: 'smtp.gmail.com',
    'port' => (int) (getenv('MAIL_PORT') ?: 465),
    'username' => getenv('MAIL_USERNAME') ?: 'kinxedo78@gmail.com',
    'password' => getenv('MAIL_PASSWORD') ?: 'orzc tvcb gezp acsc',
    'encryption' => strtolower(getenv('MAIL_ENCRYPTION') ?: 'ssl'), // ssl|tls
    'from_email' => getenv('MAIL_FROM_ADDRESS') ?: 'trungnguyen.coffee@gmail.com',
    'from_name' => getenv('MAIL_FROM_NAME') ?: 'Trung Nguyen Coffee Support',
    'base_reset_url' => getenv('RESET_PASSWORD_BASE_URL')

        ?: 'http://localhost/oss_trung_nguyen_coffee_nhat_thanh/views/customer/resetpassword.php',
];

