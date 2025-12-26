<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $stmt = $mysqli->prepare('INSERT INTO departments (name) VALUES (?)');
    $stmt->bind_param('s', $name);
    $stmt->execute();
    header('Location: list.php');
    exit;
}
?>
<?php include __DIR__ . '/../header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h3 class="mb-0"><i class="bi bi-building-add me-2"></i>Add Department</h3>
  <a class="btn btn-outline-secondary" href="list.php"><i class="bi bi-arrow-left me-1"></i>Back to List</a>
</div>

<div class="card app-card">
  <div class="card-body">
    <form method="post">
      <div class="row">
        <div class="col-md-8">
          <div class="mb-4">
            <label class="form-label">Department Name <span class="text-danger">*</span></label>
            <input name="name" class="form-control form-control-lg" placeholder="Enter department name" required>
          </div>
        </div>
      </div>
      <hr>
      <div class="d-flex justify-content-end gap-2">
        <a class="btn btn-outline-secondary" href="list.php"><i class="bi bi-x-circle me-1"></i>Cancel</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Save Department</button>
      </div>
    </form>
  </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
