<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';
require_login();

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: list.php'); exit; }

$departments = $mysqli->query('SELECT id, name FROM departments ORDER BY name');
$stmt = $mysqli->prepare('SELECT * FROM employees WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$emp = $res->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $department_id = $_POST['department_id'] ?: null;
    $joining_date = $_POST['joining_date'] ?: null;
    $blood_group = $_POST['blood_group'];
    $salary = $_POST['salary'] ?: 0;
    $dob = $_POST['dob'] ?: null;

    $stmt = $mysqli->prepare('UPDATE employees SET name=?, city=?, state=?, phone=?, email=?, department_id=?, joining_date=?, blood_group=?, salary=?, dob=? WHERE id=?');
    $stmt->bind_param('ssssisssdsi', $name, $city, $state, $phone, $email, $department_id, $joining_date, $blood_group, $salary, $dob, $id);
    $stmt->execute();
    header('Location: list.php');
    exit;
}
?>
<?php include __DIR__ . '/../header.php'; ?>
<h3>Edit Employee</h3>
<form method="post">
  <div class="row">
    <div class="col-md-6">
      <div class="mb-2"><label>Name</label><input name="name" class="form-control" value="<?=htmlspecialchars($emp['name'])?>" required></div>
      <div class="mb-2"><label>City</label><input name="city" class="form-control" value="<?=htmlspecialchars($emp['city'])?>"></div>
      <div class="mb-2"><label>State</label><input name="state" class="form-control" value="<?=htmlspecialchars($emp['state'])?>"></div>
      <div class="mb-2"><label>Phone</label><input name="phone" class="form-control" value="<?=htmlspecialchars($emp['phone'])?>"></div>
      <div class="mb-2"><label>Email</label><input name="email" type="email" class="form-control" value="<?=htmlspecialchars($emp['email'])?>"></div>
      <div class="mb-2"><label>Department</label>
        <select name="department_id" class="form-select">
          <option value="">--</option>
          <?php while ($d = $departments->fetch_assoc()): ?>
            <option value="<?= $d['id'] ?>" <?= $emp['department_id']==$d['id']? 'selected':'' ?>><?= htmlspecialchars($d['name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <div class="mb-2"><label>Joining Date</label><input name="joining_date" type="date" class="form-control" value="<?=htmlspecialchars($emp['joining_date'])?>"></div>
      <div class="mb-2"><label>Blood Group</label><input name="blood_group" class="form-control" value="<?=htmlspecialchars($emp['blood_group'])?>"></div>
      <div class="mb-2"><label>Salary</label><input name="salary" type="number" step="0.01" class="form-control" value="<?=htmlspecialchars($emp['salary'])?>"></div>
      <div class="mb-2"><label>DOB</label><input name="dob" type="date" class="form-control" value="<?=htmlspecialchars($emp['dob'])?>"></div>
      <div class="mt-3"><button class="btn btn-primary">Save</button> <a class="btn btn-secondary" href="list.php">Cancel</a></div>
    </div>
  </div>
</form>
<?php include __DIR__ . '/../footer.php'; ?>
