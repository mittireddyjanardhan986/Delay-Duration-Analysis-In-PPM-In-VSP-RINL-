<?php
header('Content-Type: application/json');
include("db.php");

// Disable error reporting to prevent any warnings/notices from breaking JSON response
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
function display_data_shopcode_major_delays($fromDate, $toDate, $shopCode) {
    global $con;

    $stmt = $con->prepare("
        SELECT SUM(DELAY_DURN) AS durn, SHOP_CODE, EQPT
        FROM delays
        WHERE DELAY_DURN > 5
          AND SHOP_CODE = ?
          AND DEL_DATE BETWEEN ? AND ?
        GROUP BY EQPT;
    ");
    if (!$stmt) {
        echo json_encode(["error" => "Preparation failed: " . $con->error]);
        return;
    }
    $stmt->bind_param("iss", $shopCode, $fromDate, $toDate);
    if (!$stmt->execute()) {
        echo json_encode(["error" => "Execution failed: " . $stmt->error]);
        return;
    }
    $result = $stmt->get_result();

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}

function display_data_date_wise_delays($fromDate, $toDate, $shopCode) {
    global $con;

    $stmt = $con->prepare("
        SELECT Sum(DELAY_DURN) as durn, EQPT from delays where SHOP_CODE=? AND DEL_DATE BETWEEN ? AND ? GROUP BY EQPT;
    ");
    if (!$stmt) {
        echo json_encode(["error" => "Preparation failed: " . $con->error]);
        return;
    }
    $stmt->bind_param("iss", $shopCode, $fromDate, $toDate);
    if (!$stmt->execute()) {
        echo json_encode(["error" => "Execution failed: " . $stmt->error]);
        return;
    }
    $result = $stmt->get_result();

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}

function display_data_continued_delays($fromDate, $toDate, $shopCode) {
    global $con;

    $stmt = $con->prepare("
        SELECT EQPT,sum(DELAY_DURN) as durn,SHOP_CODE from delays where CONTINUED='Y' and SHOP_CODE=? and DEL_DATE BETWEEN ? and ? GROUP BY EQPT;
    ");
    if (!$stmt) {
        echo json_encode(["error" => "Preparation failed: " . $con->error]);
        return;
    }
    $stmt->bind_param("iss", $shopCode, $fromDate, $toDate);
    if (!$stmt->execute()) {
        echo json_encode(["error" => "Execution failed: " . $stmt->error]);
        return;
    }
    $result = $stmt->get_result();

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}

function display_data_raw_material_delays($fromDate, $toDate, $shopCode) {
    global $con;

    $stmt = $con->prepare("
        SELECT SUM(DELAY_DURN) as durn, MATERIAL from delays where SHOP_CODE=? and DEL_DATE BETWEEN ? and ? GROUP BY MATERIAL;
    ");
    if (!$stmt) {
        echo json_encode(["error" => "Preparation failed: " . $con->error]);
        return;
    }
    $stmt->bind_param("iss", $shopCode, $fromDate, $toDate);
    if (!$stmt->execute()) {
        echo json_encode(["error" => "Execution failed: " . $stmt->error]);
        return;
    }
    $result = $stmt->get_result();

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}

function display_data_agency_wise_delays($fromDate, $toDate, $shopCode){
    global $con;

    $stmt = $con->prepare("
        SELECT sum(DELAY_DURN) as durn, AGENCY_CODE 
        FROM delays 
        WHERE DEL_DATE BETWEEN ? AND ? 
        AND AGENCY_CODE = ? 
        GROUP BY AGENCY_CODE;
    ");
    
    if (!$stmt) {
        echo json_encode(["error" => "Preparation failed: " . $con->error]);
        return;
    }

    $stmt->bind_param("sss", $fromDate, $toDate, $shopCode);
    
    if (!$stmt->execute()) {
        echo json_encode(["error" => "Execution failed: " . $stmt->error]);
        return;
    }
    
    $result = $stmt->get_result();

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}


function confirm_login_check($username, $password) {
    global $pdo; // Assuming $pdo is defined in db.php

    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute the query securely
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            echo json_encode(['status' => 'success', 'message' => 'Login successful']);
        } else {
            // Login failed
            echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
        }
    } catch (PDOException $e) {
        // Handle the database connection error
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

function display_data_Conveyor_delays($fromDate, $toDate){
    global $con;

    $stmt = $con->prepare("
        SELECT EQPT, SUM(DELAY_DURN) AS durn 
        FROM delays 
        WHERE EQPT LIKE '%CONV-%' 
          AND DEL_DATE BETWEEN ? AND ? 
        GROUP BY EQPT;
    ");
    if (!$stmt) {
        echo json_encode(["error" => "Preparation failed: " . $con->error]);
        return;
    }
    $stmt->bind_param("ss", $fromDate, $toDate);
    if (!$stmt->execute()) {
        echo json_encode(["error" => "Execution failed: " . $stmt->error]);
        return;
    }
    $result = $stmt->get_result();

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}


?>
