<?php
include 'connect.php';

if(isset($_GET['filter_date1'])) {
    $date1 = $_GET['filter_date1'];
} else {
    $date1 = date("Y-m-d");
}
if(isset($_GET['filter_date2'])) {
    $date2 = $_GET['filter_date2'];
} else {
    $date2 = date("Y-m-d");
}

// ตรวจสอบรูปแบบของวันที่
$date1 = mysqli_real_escape_string($conn, $date1);
$date2 = mysqli_real_escape_string($conn, $date2);

// ตั้งค่า Header สำหรับไฟล์ CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=queue_export.csv');

// สร้างตัวแปรสำหรับเขียนข้อมูล
$output = fopen('php://output', 'w');

// เขียนหัวตารางลงไฟล์ CSV
fputcsv($output, ['ลำดับคิว', 'วันที่', 'ลูกค้า', 'ที่อยู่', 'สินค้า', 'จำนวน', 'สถานะ']);

// ดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT * FROM queue WHERE queue_date >= '$date1' AND queue_date <= '$date2' ORDER BY id ASC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    // แปลงค่าสถานะเป็นข้อความภาษาไทย
    if ($row['status'] == 'success') {
        $status_text = 'สำเร็จ';
    } elseif ($row['status'] == 'cancel') {
        $status_text = 'ยกเลิก';
    } else {
        $status_text = 'รอดำเนินการ';
    }

    // เขียนข้อมูลแต่ละแถวลงใน CSV
    fputcsv($output, [
        $row['queue_number'],
        $row['queue_date'],
        $row['Customer'],
        $row['address'],
        $row['Product'],
        $row['Quantity'],
        $status_text
    ]);
}

// ปิดไฟล์ CSV
fclose($output);
exit();
?>
