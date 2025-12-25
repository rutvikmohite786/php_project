<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        $stmt = $mysqli->prepare('UPDATE salary_records SET is_paid=1, paid_at = NOW() WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}
header('Location: list.php');
exit;
