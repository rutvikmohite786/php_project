<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';
require_login();

$res = $mysqli->query('SELECT * FROM departments ORDER BY name');
?>
<?php include __DIR__ . '/../header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Departments</h3>
  <a class="btn btn-success" href="add.php">Add Department</a>
</div>
<table class="table table-sm table-striped">
  <thead><tr><th>ID</th><th>Name</th></tr></thead>
  <tbody>
    <?php while ($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../footer.php'; ?>
