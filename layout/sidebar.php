<!-- Mobile Navbar -->
<nav class="navbar bg-white d-md-none px-3 border-bottom">
  <button class="btn btn-outline-secondary" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">☰</button>
  <span class="navbar-brand ms-2 fs-small">Al-Rehman IT Institute</span>
</nav>

<!-- Mobile Sidebar -->
<div class="offcanvas offcanvas-start text-bg-dark" id="mobileSidebar">
  <div class="offcanvas-header">
    <h6>Al-Rehman IT Institute</h6>
    <button class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <nav class="nav flex-column gap-2">
      <a class="nav-link text-white" href="/academy_system02/index.php">Manage Students</a>
      <a class="nav-link text-white" href="/academy_system02/pages/courses.php">Courses</a>
      <a class="nav-link text-white" href="/academy_system02/pages/custom_report.php">Collect Fee</a>
    </nav>

    <div class="mt-auto">
      <hr>
      <a href="../logout.php" class="btn btn-danger w-100">Logout</a>
    </div>
  </div>
</div>

<!-- Desktop Sidebar -->
<aside class="sidebar">
  <div class="d-flex flex-column align-items-center">
    <img src="/academy_system02/assets/img/logo.png" alt="Al-Rehman IT Institute Logo" style="width: 160px; height: auto; margin-bottom: -48px;"/>
  <p class="text-white text-center">Al-Rehman IT Institute</p>
  </div>
  <hr class="border-secondary" style="margin-top: -8px;">
  <nav class="nav flex-column gap-2 px-3">
    <a class="nav-link active text-white" href="/academy_system02/index.php"><i class="bi bi-person me-2"></i>Manage Students</a>
    <a class="nav-link text-white" href="/academy_system02/pages/courses.php"><i class="bi bi-book me-2"></i>Manage Courses</a>
    <a class="nav-link text-white" href="/academy_system02/pages/custom_report.php"><i class="bi bi-file-earmark me-2"></i>Custom Report</a>
  </nav>

  <div class="mt-auto p-3">
    <hr class="border-secondary">
    <div class="d-flex align-items-center text-white mb-3">
      <i class="bi bi-person-circle fs-4 me-2"></i>
      <div>
        <small>Aamir Naseem(Admin)</small><br>
        <span class="text-white">admin@academy.com</span>
      </div>
    </div>
    <a href="../logout.php" class="btn btn-danger w-100">Logout</a>
  </div>
</aside>