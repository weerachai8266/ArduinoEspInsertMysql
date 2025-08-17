<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['action'])) {
    $id = intval($_POST['id']); // แปลงให้เป็นตัวเลข ป้องกัน SQL Injection
    $status = $_POST['action'];

    // ตรวจสอบค่าที่รับมา
    if (!in_array($status, ['success', 'cancel'])) {
        echo "Error: Invalid status";
        exit;
    }

    $sql = "UPDATE queue SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo "อัปเดตสำเร็จ";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
