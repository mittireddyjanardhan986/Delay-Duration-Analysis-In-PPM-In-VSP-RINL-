<?php
include("db/db_functions.php");

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $delayDate = $_POST['delayDate'];
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $shopCode = $_POST['shopCode'];
    $eqpt = $_POST['eqpt'];
    $delayDesc = $_POST['delayDesc'];
    $delayDuration = $_POST['delayDuration'];
    $continued = $_POST['continued'];

    // Prepare and bind
    $stmt = $con->prepare("INSERT INTO delays (DEL_DATE, DELAY_FROM, DELAY_TO, SHOP_CODE, EQPT, REMARKs, DELAY_DURN, CONTINUED) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(["success" => false, "error" => "Preparation failed: " . $con->error]);
        return;
    }
    $stmt->bind_param("ssssssis", $delayDate, $fromDate, $toDate, $shopCode, $eqpt, $delayDesc, $delayDuration, $continued);
    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "error" => "Execution failed: " . $stmt->error]);
        return;
    }

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
}
?>
