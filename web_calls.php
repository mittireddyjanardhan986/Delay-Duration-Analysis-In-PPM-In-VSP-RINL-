<?php
include("db/db_functions.php");

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['fromDate']) && isset($_GET['toDate']) && isset($_GET['shopCode']) && isset($_GET['reportType'])) {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $shopCode = $_GET['shopCode'];
    $reportType = $_GET['reportType'];

    // Log the received parameters for debugging
    error_log("Received Parameters: fromDate=$fromDate, toDate=$toDate, shopCode=$shopCode, reportType=$reportType");

    switch ($reportType) {
        case 'major_delays':
            display_data_shopcode_major_delays($fromDate, $toDate, $shopCode);
            break;
        case 'date_wise_delays':
            display_data_date_wise_delays($fromDate, $toDate, $shopCode);
            break;
        case 'continued_delays':
            display_data_continued_delays($fromDate, $toDate, $shopCode);
            break;
        case 'raw_material_delays':
            display_data_raw_material_delays($fromDate, $toDate, $shopCode);
            break;
        case 'agency_wise_delays':
            display_data_agency_wise_delays($fromDate, $toDate, $shopCode);
            break;
        default:
            echo json_encode(["error" => "Invalid report type"]);
            error_log("Invalid report type: $reportType"); // Log invalid report type
            break;
    }
} 
else if (isset($_GET['reportType']) && $_GET['reportType'] == 'Conveyor_delays') {
    $fromDate = $_GET['fromDate'] ?? '';
    $toDate = $_GET['toDate'] ?? '';
    display_data_Conveyor_delays($fromDate, $toDate);
}

else if (isset($_GET['reportType']) && $_GET['reportType'] == 'login_check') {
    $username = isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '';
    $password = isset($_GET['password']) ? htmlspecialchars($_GET['password']) : '';
    confirm_login_check($username, $password);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
