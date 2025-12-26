<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';
require_login();

$draw = intval($_GET['draw'] ?? $_POST['draw'] ?? 0);
$start = intval($_GET['start'] ?? $_POST['start'] ?? 0);
$length = intval($_GET['length'] ?? $_POST['length'] ?? 10);
$search = $mysqli->real_escape_string($_GET['search']['value'] ?? $_POST['search']['value'] ?? '');

$columns = ['id','name','department_name','phone','email','salary'];
$order_col = intval($_GET['order'][0]['column'] ?? $_POST['order'][0]['column'] ?? 0);
$order_dir = ($_GET['order'][0]['dir'] ?? $_POST['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
$order_by = $columns[$order_col] ?? 'id';

// total records
$totalResult = $mysqli->query('SELECT COUNT(*) AS cnt FROM employees');
$totalRow = $totalResult->fetch_assoc();
$recordsTotal = intval($totalRow['cnt']);

$base = "FROM employees e LEFT JOIN departments d ON e.department_id = d.id";
$where = '';
if ($search !== '') {
    $s = "%" . $mysqli->real_escape_string($search) . "%";
    $where = " WHERE (e.name LIKE '".$s."' OR e.phone LIKE '".$s."' OR e.email LIKE '".$s."' OR d.name LIKE '".$s."')";
}

$filteredResult = $mysqli->query('SELECT COUNT(*) AS cnt ' . $base . $where);
$filteredRow = $filteredResult->fetch_assoc();
$recordsFiltered = intval($filteredRow['cnt']);

$sql = "SELECT e.id, e.name, d.name AS department_name, e.phone, e.email, e.salary " . $base . $where . " ORDER BY " . $order_by . " " . $order_dir . " LIMIT " . $start . "," . $length;

$data = [];
$res = $mysqli->query($sql);
while ($row = $res->fetch_assoc()) {
    $dept = $row['department_name'] 
        ? '<span class="badge bg-light text-dark border">' . htmlspecialchars($row['department_name']) . '</span>'
        : '<span class="text-muted">—</span>';
    
    $actions = '<div class="btn-group" role="group">'
             . '<a class="btn btn-sm btn-outline-primary" title="Edit" href="edit.php?id='.$row['id'].'"><i class="bi bi-pencil"></i></a> '
             . '<a class="btn btn-sm btn-outline-danger" title="Delete" href="delete.php?id='.$row['id'].'" onclick="return confirm(\'Are you sure you want to delete this employee?\')"><i class="bi bi-trash"></i></a>'
             . '</div>';
    
    $data[] = [
        '<span class="badge bg-secondary">#' . $row['id'] . '</span>',
        '<strong>' . htmlspecialchars($row['name']) . '</strong>',
        $dept,
        htmlspecialchars($row['phone']),
        htmlspecialchars($row['email']),
        '<strong>$' . number_format($row['salary'], 2) . '</strong>',
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
