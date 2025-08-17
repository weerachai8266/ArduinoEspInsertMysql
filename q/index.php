<?php
include 'connect.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

// // ดึงข้อมูลจากฐานข้อมูล
// $sql_show = "SELECT * FROM queue WHERE status = 'pending'  ORDER BY id ASC";
// $result_show = $conn->query($sql_show);

// ถ้าเป็นการเรียกใช้จาก AJAX ให้ส่งข้อมูลกลับไปเฉพาะตาราง
if (isset($_GET['fetch'])) {
    $sql_show = "SELECT * FROM queue WHERE status = 'pending' ORDER BY id ASC";
    $result_show = $conn->query($sql_show);

    while ($row = $result_show->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['queue_number']}</td>";
        echo "<td>{$row['Customer']}</td>";
        echo "<td>{$row['address']}</td>";
        echo "<td>{$row['Product']}</td>";
        echo "<td>{$row['Quantity']}</td>";
        echo "</tr>";
    }
    exit(); // ออกจาก PHP เพื่อป้องกันการโหลด HTML อื่นๆ
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>คิวงาน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateQueue(id, action) {
            fetch('update_queue.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + id + '&action=' + action
            }).then(response => response.text())
            .then(data => { 
                alert(data); 
                location.reload(); 
            });
        }
        function updatePendingQueue() {
            fetch("index.php?fetch=1")
            .then(response => response.text())
            .then(data => {
                document.getElementById("pending-queue").innerHTML = data;
            })
            .catch(error => console.error("Error:", error));
        }

        // โหลดข้อมูลทุก 5 วินาที
        setInterval(updatePendingQueue, 5000);

        // โหลดข้อมูลครั้งแรกทันทีที่หน้าเว็บโหลดเสร็จ
        document.addEventListener("DOMContentLoaded", updatePendingQueue);
    </script>
</head>
<body class="bg-light">

<!-- <h1 class="text-center">คิวงาน</h1> -->
<div class="container mt-3">
    <div class="border border-info p-1 bg-info text-white rounded mb-3 text-center">
        <h1 class="m-0">
            <a href="add_queue.php" class="text-white text-decoration-none d-block">
                คิวงานที่รอดำเนินการ
            </a>
        </h1>
    </div>

    <!-- <a href="add_queue.php" class="btn btn-primary mb-3">+ เพิ่มคิวใหม่</a>
    <a href="queue_history.php" class="btn btn-info mb-3">ดูประวัติคิว</a> -->

    <table class="table table-bordered text-center">
        <thead>
            <tr class="table-primary">
                <th>ลำดับคิว</th>
                <th>ลูกค้า</th>
                <th>ที่อยู่</th>
                <th>สินค้า</th>
                <th>จำนวน</th>
                <!-- <th>สถานะ</th> -->
                <!-- <th>การจัดการ</th> -->
            </tr>
        </thead>
        <tbody id="pending-queue">
            <!-- ข้อมูลจาก AJAX จะแสดงที่นี่ -->
        </tbody>
    </table>
</div>

</body>
</html>
