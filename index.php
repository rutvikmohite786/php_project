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
<div class="login-wrap">
	<div class="row justify-content-center w-100">
		<div class="col-md-5 col-lg-4">
			<div class="card app-card">
				<div class="card-body text-center">
					<div class="mb-4">
						<i class="bi bi-shield-lock" style="font-size: 3rem; color: #64748b;"></i>
					</div>
					<h4 class="card-title mb-2 fw-bold">Admin Login</h4>
					<p class="text-muted mb-4">Sign in to manage employees, departments and salaries.</p>
					<?php if (!empty($error)): ?>
						<div class="alert alert-danger text-start">
							<i class="bi bi-exclamation-triangle me-2"></i><?=htmlspecialchars($error)?>
						</div>
					<?php endif; ?>
					<form method="post" action="login.php">
						<div class="mb-3 text-start">
							<label class="form-label fw-semibold">Username</label>
							<div class="input-group">
								<span class="input-group-text"><i class="bi bi-person"></i></span>
								<input name="username" class="form-control" placeholder="Enter username" required>
							</div>
						</div>
						<div class="mb-4 text-start">
							<label class="form-label fw-semibold">Password</label>
							<div class="input-group">
								<span class="input-group-text"><i class="bi bi-lock"></i></span>
								<input name="password" type="password" class="form-control" placeholder="Enter password" required>
							</div>
						</div>
						<div class="d-grid">
							<button type="submit" class="btn btn-primary btn-lg">
								<i class="bi bi-box-arrow-in-right me-2"></i>Login
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include 'footer.php'; ?>
