<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_login();
?>
<?php include 'header.php'; ?>
<div class="row">
  <div class="col-md-3">
    <div class="list-group">
      <a class="list-group-item" href="/php_project/employees/list.php">Employees</a>
      <a class="list-group-item" href="/php_project/departments/list.php">Departments</a>
      <a class="list-group-item" href="/php_project/salary/list.php">Salaries</a>
    </div>
  </div>
  <div class="col-md-9">
    <h3>Dashboard</h3>
    <p>Welcome to the Employee Management System. Use the menu to manage employees, departments and salaries.</p>
  </div>
</div>
<?php include 'footer.php'; ?>
