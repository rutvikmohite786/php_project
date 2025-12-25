<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
if (is_logged_in()) {
		header('Location: /php_project/dashboard.php');
		exit;
}
$error = '';
?>
<?php include 'header.php'; ?>
<div class="row justify-content-center">
	<div class="col-md-6">
		<h3>Admin Login</h3>
		<?php if (!empty($error)): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
		<form method="post" action="login.php">
			<div class="mb-3">
				<label class="form-label">Username</label>
				<input name="username" class="form-control" required>
			</div>
			<div class="mb-3">
				<label class="form-label">Password</label>
				<input name="password" type="password" class="form-control" required>
			</div>
			<button class="btn btn-primary">Login</button>
		</form>
	</div>
</div>
<?php include 'footer.php'; ?>
