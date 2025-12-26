<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';
require_login();

$draw = intval($_GET['draw'] ?? $_POST['draw'] ?? 0);
$start = intval($_GET['start'] ?? $_POST['start'] ?? 0);
$length = intval($_GET['length'] ?? $_POST['length'] ?? 10);
$search = $mysqli->real_escape_string($_GET['search']['value'] ?? $_POST['search']['value'] ?? '');

$columns = ['sr.id', 'e.name', 'sr.month', 'sr.year', 'sr.net_salary', 'sr.is_paid'];
$order_col = intval($_GET['order'][0]['column'] ?? $_POST['order'][0]['column'] ?? 0);
$order_dir = ($_GET['order'][0]['dir'] ?? $_POST['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
$order_by = $columns[$order_col] ?? 'sr.id';

$totalResult = $mysqli->query('SELECT COUNT(*) AS cnt FROM salary_records');
$totalRow = $totalResult->fetch_assoc();
$recordsTotal = intval($totalRow['cnt']);

$base = "FROM salary_records sr JOIN employees e ON sr.employee_id = e.id";
$where = '';
if ($search !== '') {
    $s = "%" . $mysqli->real_escape_string($search) . "%";
    $where = " WHERE (e.name LIKE '".$s."' OR sr.year LIKE '".$s."' OR sr.month LIKE '".$s."')";
}

$filteredResult = $mysqli->query('SELECT COUNT(*) AS cnt ' . $base . $where);
$filteredRow = $filteredResult->fetch_assoc();
$recordsFiltered = intval($filteredRow['cnt']);

$sql = "SELECT sr.id, e.name AS employee_name, sr.month, sr.year, sr.net_salary, sr.is_paid " . $base . $where . " ORDER BY " . $order_by . " " . $order_dir . " LIMIT " . $start . "," . $length;

$data = [];
$res = $mysqli->query($sql);
while ($r = $res->fetch_assoc()) {
    $monthName = date('F', mktime(0,0,0,$r['month'],1));
    $status = $r['is_paid'] 
        ? '<span class="badge bg-success">Paid</span>'
        : '<span class="badge bg-warning text-dark">Pending</span>';
    
    $actions = '';
    if (!$r['is_paid']) {
        $actions = '<form method="post" action="mark_paid.php" style="display:inline-block">'
                  . '<input type="hidden" name="id" value="'.$r['id'].'">'
                  . '<button type="submit" class="btn btn-sm btn-success">'
                  . '<i class="bi bi-cash-stack me-1"></i>Mark Paid</button></form>';
    } else {
        $actions = '<span class="text-muted small">Completed</span>';
    }
    
    $data[] = [
        '<span class="badge bg-secondary">#' . $r['id'] . '</span>',
        '<strong>' . htmlspecialchars($r['employee_name']) . '</strong>',
        '<span class="badge bg-light text-dark border">' . $monthName . '</span>',
        '<strong>' . $r['year'] . '</strong>',
        '<strong class="text-success">$' . number_format($r['net_salary'], 2) . '</strong>',
        $status,
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
