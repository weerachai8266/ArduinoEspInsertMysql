<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>บันทึกค่าลงใน MySQL</title>
</head>
<body>
    <h1>บันทึกค่าลงใน MySQL</h1>
    <form action="upload.php" method="post">
        <label for="value1">ค่า 1:</label>
        <input type="number" id="value1" name="value1" required><br><br>
        <label for="value2">ค่า 2:</label>
        <input type="number" id="value2" name="value2" required><br><br>
        <label for="value3">ค่า 3:</label>
        <input type="number" id="value3" name="value3" required><br><br>
        <input type="submit" value="บันทึก">
    </form>
    <br><br>
    <?php
        $servername = "192.168.1.31";
        $username = "root";
        $password = "IN673nJuQV5YP5kI";
        $dbname = "test";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $sql = "SELECT * FROM t1 ORDER by id DESC LIMIT 10";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            echo "<table border='1'>
                    <th>val1</th>
                    <th>val2</th>
                    <th>val3</th>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['val_1']."</td>";
                echo "<td>".$row['val_2']."</td>";
                echo "<td>".$row['val_3']."</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "0 results";
        }
        $conn->close();
    ?>
</body>
</html