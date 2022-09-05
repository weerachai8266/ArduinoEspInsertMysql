<?php
$servername = "192.168.1.31"; //192.168.1.31
// REPLACE with your Database name
$dbname = "power_meter";
// REPLACE with Database user
$username = "root"; //phpmyadmin
// REPLACE with Database user password
$password = "1234"; //raspberry
// Create connection + Check connection 
$conn = new mysqli($servername, $username, $password, $dbname);

$GVoltage = $_GET['Voltage'];
$GCurrent = $_GET['Current'];
$GFrequency = $_GET['Frequency'];
$GPower = $_GET['Power'];
$GPF = $_GET['PF'];
$GEnergy = $_GET['Energy'];
$GTem = $_GET['Tem'];
$GHum = $_GET['Hum'];

//http://192.168.1.31/post.php?vol=20&curr=30&power=40&freq=50&pf=50
    
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);    
// Check connection   
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
        
$sql = "INSERT INTO power_room403 (
            Voltage, 
            Current, 
            Frequency, 
            Power, 
            PF, 
            Energy, 
            Tem,
            Hum
        )
        VALUES (
            '" . $GVoltage . "', 
            '" . $GCurrent . "', 
            '" . $GFrequency . "', 
            '" . $GPower . "', 
            '" . $GPF . "', 
            '" . $GEnergy . "', 
            '" . $GTem . "', 
            '" . $GHum . "'
        )";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} 
else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
    
$conn->close();

?>
