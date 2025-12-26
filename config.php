<?php
// Enable exceptions for better error handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$DB_HOST = '127.0.0.1';
$DB_NAME = 'employee_mgmt';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $mysqli->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    die('DB connect error: ' . $e->getMessage());
}

date_default_timezone_set('UTC');
?>
