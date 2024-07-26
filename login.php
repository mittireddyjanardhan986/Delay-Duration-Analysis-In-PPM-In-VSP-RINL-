<?php
$con = new mysqli("localhost", "root", "", "vspproject");

if ($con->connect_error) {
    die("Failed to connect: " . $con->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt_result = $stmt->get_result();

    if ($stmt_result->num_rows > 0) {
        $data = $stmt_result->fetch_assoc();
        if ($data['password'] === $password) {
            header("Location: navbar.html");
            exit();
        } else {
            echo "<h2>Invalid username or password</h2>";
        }
    } else {
        echo "<h2>Invalid username or password</h2>";
    }

    $stmt->close();
    $con->close();
}
?>
