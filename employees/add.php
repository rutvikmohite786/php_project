<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';
require_login();

$error = '';
$departments = $mysqli->query('SELECT id, name FROM departments ORDER BY name');

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
        $email_normalized = strtolower(trim($email));
        
        // Clean up any existing bad email data FIRST (before checking/inserting)
        // This fixes old records with email="0" or empty, so they don't conflict
        $mysqli->query("UPDATE employees SET email = CONCAT('temp_', id, '@fixed.local') WHERE (email = '0' OR email = '' OR email IS NULL)");
        
        // Check for real duplicates (excluding 0 and empty)
        $check_stmt = $mysqli->prepare('SELECT id, name, email FROM employees WHERE email = ? LIMIT 1');
        $check_stmt->bind_param('s', $email_normalized);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $existing = $check_result->fetch_assoc();
            // Skip if it's a temp email (from cleanup)
            if (strpos($existing['email'], '@fixed.local') === false) {
                $error = 'An employee with email "' . htmlspecialchars($existing['email']) . '" already exists (ID: ' . $existing['id'] . ', Name: ' . htmlspecialchars($existing['name']) . '). Please use a different email.';
            } else {
                // It's a temp email from cleanup, safe to proceed
                // Insert employee with the ORIGINAL email user entered
                try {
                    $stmt = $mysqli->prepare('INSERT INTO employees (name, city, state, phone, email, department_id, joining_date, blood_group, salary, dob) VALUES (?,?,?,?,?,?,?,?,?,?)');
                    $stmt->bind_param('ssssisssds', $name, $city, $state, $phone, $email_normalized, $department_id, $joining_date, $blood_group, $salary, $dob);
                    
                    if ($stmt->execute()) {
                        header('Location: list.php');
                        exit;
                    } else {
                        $error = 'Error adding employee: ' . $stmt->error;
                    }
                } catch (mysqli_sql_exception $e) {
                    $error = 'Error: ' . htmlspecialchars($e->getMessage());
                }
            }
        } else {
            // No duplicate found, insert employee with the ORIGINAL email user entered
            try {
                $stmt = $mysqli->prepare('INSERT INTO employees (name, city, state, phone, email, department_id, joining_date, blood_group, salary, dob) VALUES (?,?,?,?,?,?,?,?,?,?)');
                $stmt->bind_param('ssssisssds', $name, $city, $state, $phone, $email_normalized, $department_id, $joining_date, $blood_group, $salary, $dob);
                
                if ($stmt->execute()) {
                    header('Location: list.php');
                    exit;
                } else {
                    $error = 'Error adding employee: ' . $stmt->error;
                }
            } catch (mysqli_sql_exception $e) {
                $error_msg = $e->getMessage();
                
                // Check if it's a duplicate email error
                if (strpos($error_msg, 'Duplicate entry') !== false && (strpos($error_msg, 'email') !== false || strpos($error_msg, 'employees.email') !== false)) {
                    $error = 'An employee with this email address already exists. Please use a different email.';
                } else {
                    $error = 'Database error: ' . htmlspecialchars($error_msg);
                }
            }
        }
    }
}
?>
<?php include __DIR__ . '/../header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h3 class="mb-0"><i class="bi bi-person-plus me-2"></i>Add Employee</h3>
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
            <input name="name" class="form-control" placeholder="Enter full name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input name="email" type="email" class="form-control" placeholder="email@example.com" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Phone <span class="text-danger">*</span></label>
            <input name="phone" class="form-control" placeholder="Enter phone number" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Date of Birth</label>
            <input name="dob" type="date" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Blood Group</label>
            <input name="blood_group" class="form-control" placeholder="e.g., A+, B+, O+">
          </div>
        </div>
        <div class="col-md-6">
          <h6 class="text-muted mb-3 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #64748b;">Work Information</h6>
          <div class="mb-3">
            <label class="form-label">Department</label>
            <select name="department_id" class="form-select">
              <option value="">Select Department</option>
              <?php while ($d = $departments->fetch_assoc()): ?>
                <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Joining Date</label>
            <input name="joining_date" type="date" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Salary</label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input name="salary" type="number" step="0.01" class="form-control" placeholder="0.00">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">City</label>
            <input name="city" class="form-control" placeholder="Enter city">
          </div>
          <div class="mb-3">
            <label class="form-label">State</label>
            <input name="state" class="form-control" placeholder="Enter state">
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-12">
          <hr>
          <div class="d-flex justify-content-end gap-2">
            <a class="btn btn-outline-secondary" href="list.php"><i class="bi bi-x-circle me-1"></i>Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Save Employee</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
<script>
(function() {
  function initFormValidation() {
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.validate !== 'undefined') {
      jQuery(document).ready(function() {
        jQuery('#employeeForm').validate({
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
          },
          submitHandler: function(form) {
            form.submit();
          }
        });
      });
    } else {
      setTimeout(initFormValidation, 100);
    }
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFormValidation);
  } else {
    initFormValidation();
  }
})();
</script>
