<?php
$DB_HOST = '127.0.0.1';
$DB_NAME = 'employee_mgmt';
$DB_USER = 'root';
$DB_PASS = '';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_error) {
    die('DB connect error: ' . $mysqli->connect_error);
}

date_default_timezone_set('UTC');
?>
