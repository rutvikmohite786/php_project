# Employee Management System (Core PHP)

1. Import the SQL schema (use phpMyAdmin or mysql CLI). See SQL provided earlier.
2. Update `config.php` with your DB credentials.
3. Create an admin user by inserting into `admins` table — generate a password hash with PHP `password_hash()`.
4. Open `index.php` in your browser and login.

Files scaffolded:
- `config.php`, `auth.php`, `header.php`, `footer.php`
- `index.php`, `login.php`, `logout.php`, `dashboard.php`
- `employees/` CRUD pages
- `departments/` add/list
- `salary/` generate/list/mark_paid
