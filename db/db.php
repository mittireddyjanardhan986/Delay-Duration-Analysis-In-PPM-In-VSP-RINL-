<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vspproject";

// Create connection using mysqli (optional if using PDO)
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// PDO DSN (Data Source Name)
$dsn = "mysql:host=$servername;dbname=$dbname";

// PDO connection and error handling
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}
?>
