<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "v_store";
$port = 3306;

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
// Thiết lập charset cho tiếng Việt
$conn->set_charset("utf8mb4");
