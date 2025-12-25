<?php
require_once __DIR__ . '/auth.php';
session_destroy();
header('Location: /php_project/index.php');
exit;
