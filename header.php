<?php
require_once __DIR__ . '/auth.php';
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Employee Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="/php_project/assets/css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
</head>
<body class="admin-layout">
<?php if (is_logged_in()): ?>
<div class="sidebar-wrapper">
  <aside class="sidebar">
    <div class="sidebar-header">
      <a href="/php_project/dashboard.php" class="sidebar-brand">
        <i class="bi bi-briefcase me-2"></i>
        <span class="brand-text">EMS</span>
      </a>
    </div>
    <nav class="sidebar-nav">
      <ul class="nav-menu">
        <li class="nav-item">
          <a href="/php_project/dashboard.php" class="nav-link">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a href="/php_project/employees/list.php" class="nav-link">
            <i class="bi bi-people"></i>
            <span>Employees</span>
          </a>
        </li>
        <li class="nav-item">
          <a href="/php_project/departments/list.php" class="nav-link">
            <i class="bi bi-building"></i>
            <span>Departments</span>
          </a>
        </li>
        <li class="nav-item">
          <a href="/php_project/salary/list.php" class="nav-link">
            <i class="bi bi-wallet2"></i>
            <span>Salaries</span>
          </a>
        </li>
      </ul>
    </nav>
    <div class="sidebar-footer">
      <a href="/php_project/logout.php" class="nav-link logout-link">
        <i class="bi bi-box-arrow-right"></i>
        <span>Logout</span>
      </a>
    </div>
  </aside>
  <div class="main-content">
    <div class="content-wrapper">
<?php else: ?>
<div class="main-content">
  <div class="content-wrapper">
<?php endif; ?>
