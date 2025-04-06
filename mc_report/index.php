<?php include 'connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $update_id = $_POST['update_id'];
    $new_status = $_POST['update'];

    $stmt = $conn->prepare("UPDATE Machine SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("si", $new_status, $update_id);
    $stmt->execute();
    $stmt->close();
    echo '<div class="alert alert-info">อัปเดตสถานะเรียบร้อยแล้ว: ' . htmlspecialchars($new_status) . '</div>';
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>รายงานเครื่องจักรหยุดเครื่อง</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e3f2fd, #ffffff);
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        td.problem-cell {
        white-space: pre-wrap;
        word-break: break-word;
        max-width: 500px;
    }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card p-4 bg-white">
                <h3 class="text-center mb-4 text-primary">🛠️ ฟอร์มแจ้งซ่อมเครื่องจักร</h3>

                <?php
                // เพิ่มข้อมูลใหม่
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
                    $machine = $_POST['machine'];
                    $problem = $_POST['problem'];
                    $status = $_POST['status'];

                    $stmt = $conn->prepare("INSERT INTO Machine (machine, problem, status) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $machine, $problem, $status);
                    $stmt->execute();
                    $stmt->close();
                    echo '<div class="alert alert-success">บันทึกข้อมูลเรียบร้อยแล้ว</div>';
                }

                // อัปเดตสถานะ
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
                    $update_id = $_POST['update_id'];
                    $stmt = $conn->prepare("UPDATE Machine SET status = 'ใช้งานได้', updated_at = NOW() WHERE id = ?");
                    $stmt->bind_param("i", $update_id);
                    $stmt->execute();
                    $stmt->close();
                    echo '<div class="alert alert-info">อัปเดตสถานะเรียบร้อยแล้ว</div>';
                }

                // ฟังก์ชันแสดง badge สถานะ
                function statusBadge($status) {
                    switch ($status) {
                        case 'ใช้งานได้': return '<span class="badge bg-success">✅ ใช้งานได้</span>';
                        case 'กำลังซ่อม': return '<span class="badge bg-warning text-dark">🧰 กำลังซ่อม</span>';
                        case 'รอซ่อม': return '<span class="badge bg-secondary">⏳ รอซ่อม</span>';
                        case 'หยุดเครื่อง': return '<span class="badge bg-danger">❌ หยุดเครื่อง</span>';
                        default: return $status;
                    }
                }
                ?>

                <!-- ฟอร์มแจ้งซ่อม -->
                <form method="POST" action="">
                    <input type="hidden" name="add" value="1">
                    <div class="mb-3">
                        <div class="mb-3">
                            <label class="form-label">ชื่อเครื่องจักร</label>
                            <select class="form-select" name="machine" required>
                                <option value="">-- เลือกเครื่องจักร --</option>
                                <option value="Grab Crane">Grab Crane</option>
                                <option value="เครื่องคัดแยก">เครื่องคัดแยก</option>
                                <option value="สายพานลำเลียง">สายพานลำเลียง</option>
                                <option value="เครื่องบด">เครื่องบด</option>
                                <option value="เครื่องป้อนวัสดุ">เครื่องป้อนวัสดุ</option>
                            </select>
                        </div>

                    </div>
                    <div class="mb-3">
                        <label class="form-label">ปัญหา</label>
                        <textarea class="form-control" name="problem" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">สถานะเริ่มต้น</label>
                        <select class="form-select" name="status" required>
                            <option value="">-- เลือกสถานะ --</option>
                            <option value="หยุดเครื่อง">❌ หยุดเครื่อง</option>
                            <option value="รอซ่อม">⏳ รอซ่อม</option>
                            <option value="กำลังซ่อม">🧰 กำลังซ่อม</option>
                            <!-- <option value="ใช้งานได้">✅ ใช้งานได้</option> -->
                        </select>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">💾 บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- รายการที่ยังไม่ปิดงาน -->
    <div class="row justify-content-center mt-5">
        <!-- <div class="col-lg-12"> -->
        <div class="container-fluid">
            <h4 class="text-danger mb-3">🔧 รายการเครื่องจักรที่ยังไม่ใช้งานได้</h4>
            <table class="table table-bordered table-striped bg-white shadow-sm">
                <thead class="table-primary">
                    <tr>
                        <th>รหัส</th>
                        <th>เครื่องจักร</th>
                        <th>ปัญหา</th>
                        <th>สถานะ</th>
                        <th>วันที่แจ้ง</th>
                        <th>อัปเดต</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM Machine WHERE status != 'ใช้งานได้' ORDER BY created_at DESC LIMIT 10");
                    if ($result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['machine']) ?></td>
                        <td class="problem-cell"><?= htmlspecialchars($row['problem']) ?></td>

                        <td><?= statusBadge($row['status']) ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <form method="POST" action="" class="d-inline">
                                <input type="hidden" name="update" value="ใช้งานได้">
                                <input type="hidden" name="update_id" value="<?= $row['id'] ?>">
                                <button class="btn btn-success btn-sm mb-1">✅ ใช้งานได้</button>
                            </form>
                            <form method="POST" action="" class="d-inline">
                                <input type="hidden" name="update" value="กำลังซ่อม">
                                <input type="hidden" name="update_id" value="<?= $row['id'] ?>">
                                <button class="btn btn-warning btn-sm mb-1">🧰 กำลังซ่อม</button>
                            </form>
                            <form method="POST" action="" class="d-inline">
                                <input type="hidden" name="update" value="รอซ่อม">
                                <input type="hidden" name="update_id" value="<?= $row['id'] ?>">
                                <button class="btn btn-secondary btn-sm mb-1">⏳ รอซ่อม</button>
                            </form>
                        </td>

                    </tr>
                    <?php
                        endwhile;
                    else:
                        echo '<tr><td colspan="6" class="text-center text-muted">ไม่มีรายการที่ยังไม่เสร็จ</td></tr>';
                    endif;
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS (Tooltip) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
</body>
</html>
