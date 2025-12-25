<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';
require_login();

$res = $mysqli->query('SELECT sr.*, e.name AS employee_name FROM salary_records sr JOIN employees e ON sr.employee_id = e.id ORDER BY sr.generated_at DESC');
?>
<?php include __DIR__ . '/../header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Salary Records</h3>
  <a class="btn btn-success" href="generate.php">Generate Salaries</a>
</div>
<table class="table table-sm table-striped">
  <thead><tr><th>#</th><th>Employee</th><th>Month</th><th>Year</th><th>Net</th><th>Paid</th><th>Actions</th></tr></thead>
  <tbody>
    <?php while ($r = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['employee_name']) ?></td>
        <td><?= $r['month'] ?></td>
        <td><?= $r['year'] ?></td>
        <td><?= number_format($r['net_salary'],2) ?></td>
        <td><?= $r['is_paid'] ? 'Yes' : 'No' ?></td>
        <td>
          <?php if (!$r['is_paid']): ?>
            <form method="post" action="mark_paid.php" style="display:inline-block">
              <input type="hidden" name="id" value="<?= $r['id'] ?>">
              <button class="btn btn-sm btn-primary">Mark Paid</button>
            </form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../footer.php'; ?>
