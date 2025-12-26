<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';
require_login();

$draw = intval($_GET['draw'] ?? $_POST['draw'] ?? 0);
$start = intval($_GET['start'] ?? $_POST['start'] ?? 0);
$length = intval($_GET['length'] ?? $_POST['length'] ?? 10);
$search = $mysqli->real_escape_string($_GET['search']['value'] ?? $_POST['search']['value'] ?? '');

$columns = ['id','name'];
$order_col = intval($_GET['order'][0]['column'] ?? $_POST['order'][0]['column'] ?? 0);
$order_dir = ($_GET['order'][0]['dir'] ?? $_POST['order'][0]['dir'] ?? 'asc') === 'asc' ? 'ASC' : 'DESC';
$order_by = $columns[$order_col] ?? 'id';

$totalResult = $mysqli->query('SELECT COUNT(*) AS cnt FROM departments');
$totalRow = $totalResult->fetch_assoc();
$recordsTotal = intval($totalRow['cnt']);

$where = '';
if ($search !== '') {
    $s = "%" . $mysqli->real_escape_string($search) . "%";
    $where = " WHERE name LIKE '".$s."'";
}

$filteredResult = $mysqli->query('SELECT COUNT(*) AS cnt FROM departments' . $where);
$filteredRow = $filteredResult->fetch_assoc();
$recordsFiltered = intval($filteredRow['cnt']);

$sql = "SELECT id, name FROM departments" . $where . " ORDER BY " . $order_by . " " . $order_dir . " LIMIT " . $start . "," . $length;

$data = [];
$res = $mysqli->query($sql);
while ($row = $res->fetch_assoc()) {
    $actions = '<a class="btn btn-sm btn-outline-primary" href="add.php?edit='.$row['id'].'" title="Edit">'
             . '<i class="bi bi-pencil me-1"></i>Edit</a>';
    
    $data[] = [
        '<span class="badge bg-secondary">#' . $row['id'] . '</span>',
        '<strong><i class="bi bi-building me-2"></i>' . htmlspecialchars($row['name']) . '</strong>',
        $actions
    ];
}

$out = [
    'draw' => $draw,
    'recordsTotal' => $recordsTotal,
    'recordsFiltered' => $recordsFiltered,
    'data' => $data
];

header('Content-Type: application/json; charset=utf-8');
echo json_encode($out);
exit;
