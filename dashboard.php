<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_login();

// Get statistics
$emp_count = $mysqli->query('SELECT COUNT(*) as cnt FROM employees')->fetch_assoc()['cnt'];
$dept_count = $mysqli->query('SELECT COUNT(*) as cnt FROM departments')->fetch_assoc()['cnt'];
$salary_count = $mysqli->query('SELECT COUNT(*) as cnt FROM salary_records')->fetch_assoc()['cnt'];
?>
<?php include 'header.php'; ?>
<div class="row g-4">
  <div class="col-12">
    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="card stat-card p-4">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <div class="h5 mb-2">Employees</div>
              <div class="display-6 fw-bold"><?= number_format($emp_count) ?></div>
            </div>
            <div class="text-muted">
              <i class="bi bi-people" style="font-size: 2.5rem;"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card stat-card p-4">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <div class="h5 mb-2">Departments</div>
              <div class="display-6 fw-bold"><?= number_format($dept_count) ?></div>
            </div>
            <div class="text-muted">
              <i class="bi bi-building" style="font-size: 2.5rem;"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card stat-card p-4">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <div class="h5 mb-2">Salary Records</div>
              <div class="display-6 fw-bold"><?= number_format($salary_count) ?></div>
            </div>
            <div class="text-muted">
              <i class="bi bi-wallet2" style="font-size: 2.5rem;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card app-card">
      <div class="card-body">
        <h5 class="mb-3">Welcome to Employee Management System</h5>
        <p class="text-muted mb-0">Manage your organization's employees, departments, and salary records efficiently. Use the sidebar navigation to access different sections.</p>
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
