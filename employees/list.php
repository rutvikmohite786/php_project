<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';
require_login();

$res = $mysqli->query('SELECT e.*, d.name AS department_name FROM employees e LEFT JOIN departments d ON e.department_id = d.id ORDER BY e.id DESC');
?>
<?php include __DIR__ . '/../header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Employees</h3>
  <a class="btn btn-success" href="add.php">Add Employee</a>
</div>
<table class="table table-sm table-striped">
  <thead><tr><th>ID</th><th>Name</th><th>Dept</th><th>Phone</th><th>Email</th><th>Salary</th><th>Actions</th></tr></thead>
  <tbody>
    <?php while ($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['department_name']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= number_format($row['salary'],2) ?></td>
        <td>
          <a class="btn btn-sm btn-primary" href="edit.php?id=<?= $row['id'] ?>">Edit</a>
          <a class="btn btn-sm btn-danger" href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../footer.php'; ?>
