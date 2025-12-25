<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $month = (int)$_POST['month'];
    $year = (int)$_POST['year'];
    $allowances = (float)($_POST['allowances'] ?? 0);
    $deductions = (float)($_POST['deductions'] ?? 0);

    $emps = $mysqli->query('SELECT id, salary FROM employees');
    while ($e = $emps->fetch_assoc()) {
        $employee_id = $e['id'];
        $base = (float)$e['salary'];
        $net = $base + $allowances - $deductions;
        $stmt = $mysqli->prepare('INSERT IGNORE INTO salary_records (employee_id, year, month, base_salary, allowances, deductions, net_salary) VALUES (?,?,?,?,?,?,?)');
        $stmt->bind_param('iiiddid', $employee_id, $year, $month, $base, $allowances, $deductions, $net);
        $stmt->execute();
    }
    header('Location: list.php');
    exit;
}
?>
<?php include __DIR__ . '/../header.php'; ?>
<h3>Generate Salaries</h3>
<form method="post">
  <div class="row">
    <div class="col-md-3 mb-2"><label>Month</label><input name="month" type="number" min="1" max="12" class="form-control" required></div>
    <div class="col-md-3 mb-2"><label>Year</label><input name="year" type="number" min="2000" class="form-control" required></div>
    <div class="col-md-3 mb-2"><label>Allowances</label><input name="allowances" type="number" step="0.01" class="form-control" value="0"></div>
    <div class="col-md-3 mb-2"><label>Deductions</label><input name="deductions" type="number" step="0.01" class="form-control" value="0"></div>
  </div>
  <div class="mt-3"><button class="btn btn-primary">Generate</button> <a class="btn btn-secondary" href="list.php">Cancel</a></div>
</form>
<?php include __DIR__ . '/../footer.php'; ?>
