<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer = $_POST['customer'];
    $product = $_POST['product'];
    $quantity = $_POST['quantity'];
    $address = $_POST['address'];
    $date = date("Y-m-d");
    // $date = date("Y-m-d", strtotime("+1 day"));


    // ตรวจสอบหมายเลขคิวล่าสุดของวันปัจจุบัน
    $sql_check = "SELECT queue_number FROM queue WHERE queue_date = '$date' ORDER BY queue_number DESC LIMIT 1";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_queue_number = intval($row['queue_number']);
        $new_queue_number = str_pad($last_queue_number + 1, 3, "0", STR_PAD_LEFT);
    } else {
        $new_queue_number = "001";
    }

    // เพิ่มข้อมูลคิวใหม่ลงในฐานข้อมูล
    $sql = "INSERT INTO queue (queue_number, Customer, address, Product, Quantity, queue_date) 
            VALUES ('$new_queue_number', '$customer', '$address', '$product', '$quantity', '$date')";

    if ($conn->query($sql) === TRUE) {
        // echo "เพิ่มคิวสำเร็จ! หมายเลขคิว: $new_queue_number";
        echo "<script>
                window.location.href = 'add_queue.php';
              </script>";
    } else {
        // echo "ผิดพลาด: " . $conn->error;
        echo "<script>alert('เกิดข้อผิดพลาด: " . $conn->error . "');</script>";
    }
}

// ดึงข้อมูลจากฐานข้อมูล
$sql_show = "SELECT * FROM queue WHERE status = 'pending'  ORDER BY id ASC";

$result_show = $conn->query($sql_show);

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการคิวงาน</title>
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
    </script>

</head>
<body class="container mt-3">

    <div class="border border-info p-1 bg-info text-white rounded mb-3">
        <h1 class="text-center">จัดการคิวงาน</h1>
    </div>
    
    <!-- Form -->
    <form action="" method="post">
        <div class="row">
            <div class="col-3">
                <div class="mb-3">
                    <label class="form-label">ลูกค้า</label>
                    <input type="text" name="customer" class="form-control" required>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label class="form-label">ที่อยู่</label>
                    <input type="text" name="address" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label class="form-label">สินค้า</label>
                    <!-- <input type="text" name="product" class="form-control" required> -->
                    <select class="form-select" required name="product">
                        <option selected></option>
                        <option value="อิฐบล็อค">อิฐบล็อค</option>
                        <!-- <option value="B">B</option> -->
                    </select>	
                </div>                    
            </div>  
            <div class="col">
                <div class="mb-3">
                    <label class="form-label">จำนวน</label>
                    <input type="number" name="quantity" class="form-control" required>
                </div>
            </div>              
        </div>   
        <button type="submit" class="btn btn-primary mb-3">เพิ่มคิว</button>
        <a href="queue_history.php" class="btn btn-primary mb-3">ประวัติคิว</a>
        <a href="index.php" class="btn btn-info mb-3">ดูคิว</a>
    </form>

    <!-- show -->
    <table class="table table-bordered text-center">
        <thead>
            <tr class="table-primary">
                <th>ลำดับคิว</th>
                <th>ลูกค้า</th>
                <th>ที่อยู่</th>
                <th>สินค้า</th>
                <th>จำนวน</th>
                <th>สถานะ</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <?php 
        while ($row = $result_show->fetch_assoc()) {
            echo "<tr>";
            // echo "<td>{$queue_order}</td>";
            echo "<td>{$row['queue_number']}</td>";
            echo "<td>{$row['Customer']}</td>";
            echo "<td>{$row['address']}</td>";
            echo "<td>{$row['Product']}</td>";
            echo "<td>{$row['Quantity']}</td>";
            // echo "<td>{$row['status']}</td>";
            echo "<td>";
            if ($row['status'] == 'success') {
                echo '<span>สำเร็จ</span>';
            } elseif ($row['status'] == 'cancel') {
                echo '<span>ยกเลิก</span>';
            } else {
                echo '<span>รอดำเนินการ</span>';
            }
            echo "</td>";
            echo "<td>
                    <button class='btn btn-success' onclick='updateQueue({$row['id']}, \"success\")'>Success</button>
                    <button class='btn btn-danger' onclick='updateQueue({$row['id']}, \"cancel\")'>Cancel</button>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>

</body>
</html>
