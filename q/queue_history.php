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

// ดึงข้อมูลคิวของวันนี้ทั้งหมด รวมทั้งที่ Success และ Cancel แล้ว
$sql = "SELECT * FROM queue WHERE queue_date >= '$date1' AND queue_date <= '$date2' ORDER BY id ASC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ประวัติคิว</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-3">
    <div class="border border-info p-1 bg-info text-white rounded mb-3">
        <h1 class="text-center">ประวัติคิว</h1>
    </div>

    <form action="" method="get">
        <div class="d-flex align-items-center mb-3">
            <a href="add_queue.php" class="btn btn-primary me-3">จัดการคิว</a>
            <a href="index.php" class="btn btn-primary me-3">ดูคิว</a>
            <div class="col-5">
                <div class="input-group me-3">
                    <label class="input-group-text">วันที่</label>
                    <input class="form-control" type="date" name="filter_date1">
                    <input class="form-control" type="date" name="filter_date2">
                    <button type="submit" class="btn btn-primary ">ตกลง</button>                
                </div>
            </div>
            <a href="export_csv.php?filter_date1=<?= $date1 ?>&filter_date2=<?= $date2 ?>" class="btn btn-success ms-auto">ดาวน์โหลด CSV</a>
        </div>
    </form> 

    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>ลำดับคิว</th>
                <th>วันที่</th>
                <th>ลูกค้า</th>
                <th>ที่อยู่</th>
                <th>สินค้า</th>
                <th>จำนวน</th>
                <th>สถานะ</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['queue_number'] ?></td>
                    <td><?= $row['queue_date'] ?></td>
                    <td><?= htmlspecialchars($row['Customer']) ?></td>
                    <td><?= $row['address'] ?></td>
                    <td><?= htmlspecialchars($row['Product']) ?></td>
                    <td><?= $row['Quantity'] ?></td>
                    <td>
                        <?php if ($row['status'] == 'success') { ?>
                            <span class="badge bg-success">สำเร็จ</span>
                        <?php } elseif ($row['status'] == 'cancel') { ?>
                            <span class="badge bg-danger">ยกเลิก</span>
                        <?php } else { ?>
                            <span class="badge bg-warning">รอดำเนินการ</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
