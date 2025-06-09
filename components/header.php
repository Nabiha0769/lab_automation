<style>
  .app-header .navbar {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.app-header .navbar h2 {
  letter-spacing: 1px;
}

.app-header .nav-link img {
  transition: box-shadow 0.3s ease;
}

.app-header .nav-link img:hover {
  box-shadow: 0 0 10px #0d6efd;
  cursor: pointer;
}

.dropdown-menu {
  border-radius: 10px;
  font-size: 14px;
}

.dropdown-item i {
  min-width: 22px;
}

</style>
<header class="app-header">
  <nav class="navbar navbar-expand-lg navbar-light px-4" style="background: linear-gradient(135deg, #1e3c72, #2a5298); box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
    <div class="container-fluid d-flex align-items-center justify-content-between">
      
      <div class="d-flex align-items-center">
        <button class="btn btn-link text-white d-block d-xl-none me-3" id="headerCollapse" type="button" aria-label="Toggle sidebar">
          <i class="ti ti-menu-2 fs-4"></i>
        </button>
        <h2 class="text-white text-capitalize mb-0 fs-4 fw-semibold"><?php echo $_SESSION['role']; ?> Dashboard</h2>
      </div>

      <ul class="navbar-nav flex-row align-items-center gap-3 mb-0">

        <li class="nav-item dropdown">
          <a class="nav-link p-0" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="assets/images/profile/user-1.jpg" alt="User Profile" width="40" height="40" class="rounded-circle border border-white" style="object-fit: cover; box-shadow: 0 0 6px rgba(255,255,255,0.8);">
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="userDropdown" style="min-width: 180px;">
            <li>
              <?php if($_SESSION['role'] === "Tester") { ?>
                <a class="dropdown-item d-flex align-items-center gap-2" href="./tester_profile.php">
                  <i class="ti ti-user fs-5 text-primary"></i>
                  <span>My Profile</span>
                </a>
              <?php } ?>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item text-danger fw-semibold" href="components/logout.php">
                <i class="ti ti-logout fs-5"></i>
                Logout
              </a>
            </li>
          </ul>
        </li>

      </ul>

    </div>
  </nav>
</header>
