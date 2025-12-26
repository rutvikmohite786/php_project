`<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';
require_login();

// Get all employees for dropdown
$employees = $mysqli->query('SELECT id, name FROM employees ORDER BY name');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $month = (int)$_POST['month'];
    $year = (int)$_POST['year'];
    $allowances = (float)($_POST['allowances'] ?? 0);
    $deductions = (float)($_POST['deductions'] ?? 0);
    $employee_ids = $_POST['employee_ids'] ?? [];

    // If no employees selected or "all" is selected, generate for all employees
    if (empty($employee_ids) || in_array('all', $employee_ids)) {
        $emps = $mysqli->query('SELECT id, salary FROM employees');
    } else {
        // Generate for selected employees only
        $placeholders = str_repeat('?,', count($employee_ids) - 1) . '?';
        $stmt = $mysqli->prepare("SELECT id, salary FROM employees WHERE id IN ($placeholders)");
        $types = str_repeat('i', count($employee_ids));
        $stmt->bind_param($types, ...$employee_ids);
        $stmt->execute();
        $emps = $stmt->get_result();
    }

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
<div class="d-flex justify-content-between align-items-center mb-4">
  <h3 class="mb-0"><i class="bi bi-calculator me-2"></i>Generate Salaries</h3>
  <a class="btn btn-outline-secondary" href="list.php"><i class="bi bi-arrow-left me-1"></i>Back to List</a>
</div>

<div class="card app-card">
  <div class="card-body">
    <form method="post">
      <div class="row g-4">
        <div class="col-md-6">
          <h6 class="text-muted mb-3 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #64748b;">Employee Selection</h6>
          <div class="mb-3">
            <label class="form-label">Select Employee(s) <span class="text-danger">*</span></label>
            <select name="employee_ids[]" class="form-select" multiple size="8" required>
              <option value="all" selected>All Employees</option>
              <?php while ($emp = $employees->fetch_assoc()): ?>
                <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['name']) ?></option>
              <?php endwhile; ?>
            </select>
            <small class="text-muted">Hold Ctrl/Cmd to select multiple employees. "All Employees" will generate for everyone.</small>
          </div>
        </div>
        <div class="col-md-6">
          <h6 class="text-muted mb-3 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #64748b;">Period Information</h6>
          <div class="mb-3">
            <label class="form-label">Month <span class="text-danger">*</span></label>
            <select name="month" class="form-select" required>
              <option value="">Select Month</option>
              <?php for($i=1; $i<=12; $i++): ?>
                <option value="<?= $i ?>"><?= date('F', mktime(0,0,0,$i,1)) ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Year <span class="text-danger">*</span></label>
            <input name="year" type="number" min="2000" max="<?= date('Y') + 1 ?>" class="form-control" value="<?= date('Y') ?>" required>
          </div>
        </div>
      </div>
      <div class="row g-4 mt-2">
        <div class="col-md-12">
          <h6 class="text-muted mb-3 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #64748b;">Salary Adjustments</h6>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Allowances</label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input name="allowances" type="number" step="0.01" class="form-control" value="0" placeholder="0.00">
                </div>
                <small class="text-muted">Additional allowances to be added</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Deductions</label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input name="deductions" type="number" step="0.01" class="form-control" value="0" placeholder="0.00">
                </div>
                <small class="text-muted">Deductions to be subtracted</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-12">
          <hr>
          <div class="d-flex justify-content-end gap-2">
            <a class="btn btn-outline-secondary" href="list.php"><i class="bi bi-x-circle me-1"></i>Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-calculator me-1"></i>Generate Salaries</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
<script>
(function() {
  function initEmployeeSelect() {
    if (typeof jQuery !== 'undefined') {
      jQuery(document).ready(function() {
        var $select = jQuery('select[name="employee_ids[]"]');
        
        // Handle "All Employees" selection
        $select.on('change', function() {
          var selected = jQuery(this).val() || [];
          var hasAll = selected.includes('all');
          
          if (hasAll) {
            // If "All Employees" is selected, deselect others
            jQuery(this).find('option').not('[value="all"]').prop('selected', false);
            jQuery(this).val(['all']);
          } else if (selected.length > 0) {
            // If specific employees selected, deselect "All Employees"
            jQuery(this).find('option[value="all"]').prop('selected', false);
          }
        });
      });
    } else {
      setTimeout(initEmployeeSelect, 100);
    }
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initEmployeeSelect);
  } else {
    initEmployeeSelect();
  }
})();
</script>
