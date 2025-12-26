<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';
require_login();

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: list.php'); exit; }

$error = '';
$departments = $mysqli->query('SELECT id, name FROM departments ORDER BY name');
$stmt = $mysqli->prepare('SELECT * FROM employees WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$emp = $res->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $city = trim($_POST['city'] ?? '') ?: null;
    $state = trim($_POST['state'] ?? '') ?: null;
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $department_id = !empty($_POST['department_id']) ? (int)$_POST['department_id'] : null;
    $joining_date = !empty($_POST['joining_date']) ? $_POST['joining_date'] : null;
    $blood_group = trim($_POST['blood_group'] ?? '') ?: null;
    $salary = !empty($_POST['salary']) ? (float)$_POST['salary'] : 0;
    $dob = !empty($_POST['dob']) ? $_POST['dob'] : null;

    // Validate required fields
    if (empty($name)) {
        $error = 'Name is required.';
    } elseif (empty($email) || $email === '0' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (empty($phone)) {
        $error = 'Phone number is required.';
    } else {
        // Normalize email (lowercase and trim)
        $email = strtolower(trim($email));
        
        // Check for duplicate email (excluding current employee, case-insensitive)
        $check_stmt = $mysqli->prepare('SELECT id FROM employees WHERE LOWER(TRIM(email)) = ? AND id != ? LIMIT 1');
        $check_stmt->bind_param('si', $email, $id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = 'An employee with this email address already exists. Please use a different email.';
        } else {
            // Update employee
            $stmt = $mysqli->prepare('UPDATE employees SET name=?, city=?, state=?, phone=?, email=?, department_id=?, joining_date=?, blood_group=?, salary=?, dob=? WHERE id=?');
            $stmt->bind_param('ssssisssdsi', $name, $city, $state, $phone, $email, $department_id, $joining_date, $blood_group, $salary, $dob, $id);
            
            if ($stmt->execute()) {
                header('Location: list.php');
                exit;
            } else {
                $error = 'Error updating employee: ' . $stmt->error;
            }
        }
    }
}
?>
<?php include __DIR__ . '/../header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Employee</h3>
  <a class="btn btn-outline-secondary" href="list.php"><i class="bi bi-arrow-left me-1"></i>Back to List</a>
</div>

<?php if (!empty($error)): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card app-card">
  <div class="card-body">
    <form method="post" id="employeeForm">
      <div class="row g-4">
        <div class="col-md-6">
          <h6 class="text-muted mb-3 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #64748b;">Personal Information</h6>
          <div class="mb-3">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input name="name" class="form-control" value="<?=htmlspecialchars($emp['name'])?>" placeholder="Enter full name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input name="email" type="email" class="form-control" value="<?=htmlspecialchars($emp['email'])?>" placeholder="email@example.com" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Phone <span class="text-danger">*</span></label>
            <input name="phone" class="form-control" value="<?=htmlspecialchars($emp['phone'])?>" placeholder="Enter phone number" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Date of Birth</label>
            <input name="dob" type="date" class="form-control" value="<?=htmlspecialchars($emp['dob'])?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Blood Group</label>
            <input name="blood_group" class="form-control" value="<?=htmlspecialchars($emp['blood_group'])?>" placeholder="e.g., A+, B+, O+">
          </div>
        </div>
        <div class="col-md-6">
          <h6 class="text-muted mb-3 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #64748b;">Work Information</h6>
          <div class="mb-3">
            <label class="form-label">Department</label>
            <select name="department_id" class="form-select">
              <option value="">Select Department</option>
              <?php while ($d = $departments->fetch_assoc()): ?>
                <option value="<?= $d['id'] ?>" <?= $emp['department_id']==$d['id']? 'selected':'' ?>><?= htmlspecialchars($d['name']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Joining Date</label>
            <input name="joining_date" type="date" class="form-control" value="<?=htmlspecialchars($emp['joining_date'])?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Salary</label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input name="salary" type="number" step="0.01" class="form-control" value="<?=htmlspecialchars($emp['salary'])?>" placeholder="0.00">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">City</label>
            <input name="city" class="form-control" value="<?=htmlspecialchars($emp['city'])?>" placeholder="Enter city">
          </div>
          <div class="mb-3">
            <label class="form-label">State</label>
            <input name="state" class="form-control" value="<?=htmlspecialchars($emp['state'])?>" placeholder="Enter state">
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-12">
          <hr>
          <div class="d-flex justify-content-end gap-2">
            <a class="btn btn-outline-secondary" href="list.php"><i class="bi bi-x-circle me-1"></i>Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Update Employee</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
<script>
$(function(){
  $('#employeeForm').validate({
    rules: {
      name: { required: true, minlength: 2 },
      email: { required: true, email: true },
      phone: { required: true, minlength: 7 },
      salary: { number: true, min: 0 }
    },
    messages: {
      name: { required: 'Enter employee name', minlength: 'At least 2 characters' },
      email: { required: 'Enter email', email: 'Enter a valid email' },
      phone: { required: 'Enter phone number', minlength: 'Enter at least 7 digits' },
      salary: { number: 'Enter a valid amount' }
    },
    errorClass: 'text-danger small',
    errorPlacement: function(error, element) {
      error.insertAfter(element);
    }
  });
});
</script>
