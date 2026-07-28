<?php
$host = "sql301.infinityfree.com";
$user = "if0_42518519";
$pass = "YOUR_PASSWORD"; // استبدلها بكلمة المرور الخاصة بك
$dbname = "if0_42518519_db_rayan";

$conn = new mysqli($host, $user, $pass, $dbname);

$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die(json_encode(["error" => "فشل الاتصال بقاعدة البيانات: " . $conn->connect_error]));
}
?>