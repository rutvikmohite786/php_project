<?php
session_start();

function is_logged_in() {
    return isset($_SESSION['admin_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: /php_project/index.php');
        exit;
    }
}

?>
