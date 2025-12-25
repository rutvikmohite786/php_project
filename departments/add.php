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
<h3>Add Department</h3>
<form method="post">
  <div class="mb-2"><label>Name</label><input name="name" class="form-control" required></div>
  <button class="btn btn-primary">Save</button> <a class="btn btn-secondary" href="list.php">Cancel</a>
</form>
<?php include __DIR__ . '/../footer.php'; ?>
