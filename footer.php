  </div>
<?php if (is_logged_in()): ?>
  </div>
</div>
<?php endif; ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Highlight active menu item
    (function() {
      if (typeof jQuery !== 'undefined') {
        jQuery(document).ready(function() {
          var currentPath = window.location.pathname;
          jQuery('.sidebar .nav-link').each(function() {
            var linkPath = jQuery(this).attr('href');
            if (currentPath.indexOf(linkPath) !== -1 || 
                (currentPath.includes('dashboard') && linkPath.includes('dashboard')) ||
                (currentPath.includes('employees') && linkPath.includes('employees')) ||
                (currentPath.includes('departments') && linkPath.includes('departments')) ||
                (currentPath.includes('salary') && linkPath.includes('salary'))) {
              jQuery(this).addClass('active');
            }
          });
        });
      }
    })();
  </script>
</body>
</html>
