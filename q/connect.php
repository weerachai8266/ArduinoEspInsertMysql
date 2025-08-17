<?php
$servername = "192.168.1.31";  // IP ของเซิร์ฟเวอร์ MySQL
$username = "root";            // ชื่อผู้ใช้ MySQL
$password = "IN673nJuQV5YP5kI"; // รหัสผ่าน
$dbname = "ch_rungrueang";      // ชื่อฐานข้อมูล

// ใช้ try-catch เพื่อจัดการข้อผิดพลาดในการเชื่อมต่อ
try {
    // เชื่อมต่อ MySQL
    $conn = new mysqli($servername, $username, $password, $dbname);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        throw new Exception("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
    }

    // ตั้งค่าภาษาให้รองรับ UTF-8
    if (!$conn->set_charset("utf8")) {
        throw new Exception("ไม่สามารถตั้งค่า character set เป็น UTF-8 ได้: " . $conn->error);
    }

    // กรณีเชื่อมต่อสำเร็จ
    // echo "เชื่อมต่อฐานข้อมูลสำเร็จ";

} catch (Exception $e) {
    // ถ้ามีข้อผิดพลาดในการเชื่อมต่อหรือการตั้งค่าภาษา
    error_log($e->getMessage());  // บันทึกข้อผิดพลาดใน log
    die("เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: " . $e->getMessage());  // แสดงข้อผิดพลาด
}
?>

