<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';
require_login();

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $mysqli->prepare('DELETE FROM employees WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
}
header('Location: list.php');
exit;
