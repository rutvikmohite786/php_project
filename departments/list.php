<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';
require_login();
?>
<?php include __DIR__ . '/../header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h3 class="mb-0"><i class="bi bi-building me-2"></i>Departments</h3>
  <div class="d-flex gap-2">
    <a class="btn btn-primary" href="add.php"><i class="bi bi-plus-lg me-1"></i>Add Department</a>
    <a class="btn btn-outline-secondary" href="/php_project/dashboard.php"><i class="bi bi-house me-1"></i>Dashboard</a>
  </div>
</div>

<div class="card app-card">
  <div class="card-body">
    <div class="table-responsive">
      <table id="departmentsTable" class="table table-hover mb-0">
        <thead><tr>
          <th>ID</th>
          <th>Department Name</th>
          <th class="text-center">Actions</th>
        </tr></thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
(function() {
  function initDataTable() {
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.DataTable !== 'undefined') {
      jQuery('#departmentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: 'data.php',
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        order: [[1,'asc']],
        columnDefs: [
          { orderable: false, targets: -1 },
          { type: 'num', targets: 0 }
        ],
        language: {
          processing: "Processing...",
          search: "Search:",
          lengthMenu: "Show _MENU_ entries",
          info: "Showing _START_ to _END_ of _TOTAL_ entries",
          infoEmpty: "Showing 0 to 0 of 0 entries",
          infoFiltered: "(filtered from _MAX_ total entries)",
          paginate: {
            first: "First",
            last: "Last",
            next: "Next",
            previous: "Previous"
          }
        }
      });
    } else {
      setTimeout(initDataTable, 100);
    }
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDataTable);
  } else {
    initDataTable();
  }
})();
</script>
<?php include __DIR__ . '/../footer.php'; ?>
