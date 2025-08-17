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

// Get form data
$value1 = $_POST["value1"];
$value2 = $_POST["value2"];
$value3 = $_POST["value3"];

// Insert data into table
$sql = "INSERT INTO t1 (val_1, val_2, val_3) VALUES ($value1, $value2, $value3)";

if ($conn->query($sql) === TRUE) {
    echo "บันทึกข้อมูลสำเร็จ";
    header("location:index.php");
} else {
    echo "Error: " . $conn->error;
}

$conn->close();