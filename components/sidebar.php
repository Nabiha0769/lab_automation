 <style>.sidebar-nav {
  width: 250px;
  background: linear-gradient(135deg, #1e3c72, #2a5298); /* attractive blue gradient */
  min-height: 100vh;
  padding-top: 20px;
  box-shadow: 4px 0 12px rgba(0, 0, 0, 0.15);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.sidebar-nav .nav {
  padding-left: 0;
  margin-bottom: 0;
}

.sidebar-nav .nav-item {
  margin-bottom: 8px;
}

.sidebar-nav .nav-section {
  padding: 10px 25px 5px;
  font-size: 13px;
  font-weight: 700;
  color: rgba(255, 255, 255, 0.6);
  text-transform: uppercase;
  letter-spacing: 1.2px;
  user-select: none;
}

.sidebar-nav .nav-link {
  display: flex;
  align-items: center;
  padding: 12px 25px;
  font-size: 16px;
  color: #d1d9e6;
  border-radius: 8px;
  text-decoration: none;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.sidebar-nav .nav-link .icon {
  margin-right: 15px;
  font-size: 20px;
  color: #aab8d4;
  transition: color 0.3s ease;
}

.sidebar-nav .nav-link:hover {
  background-color: rgba(255, 255, 255, 0.15);
  color: #ffffff;
}

.sidebar-nav .nav-link:hover .icon {
  color: #ffffff;
}

.sidebar-nav .nav-link.active {
  background-color: #0d6efd; /* bootstrap primary blue */
  color: #fff !important;
  font-weight: 600;
  box-shadow: 0 0 12px #0d6efd88;
}

.sidebar-nav .nav-link.active .icon {
  color: #fff !important;
} </style> 
<?php $currentPage = basename($_SERVER['PHP_SELF']); ?><nav class="sidebar-nav" data-simplebar> 
  <ul class="nav flex-column" id="sidebarnav">

    <li class="nav-item nav-section">Home</li>
    <li class="nav-item">
      <a class="nav-link <?php echo ($currentPage == 'dashboard.php') ? 'active' : ''; ?>" href="./dashboard.php">
        <iconify-icon icon="solar:home-smile-bold-duotone" class="icon"></iconify-icon> Dashboard
      </a>
    </li>

    <?php if ($_SESSION['role'] === "Admin") { ?>
      <li class="nav-item nav-section">User Management</li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'users.php') ? 'active' : ''; ?>" href="./users.php">
          <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="icon"></iconify-icon> Users
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'add_user.php') ? 'active' : ''; ?>" href="./add_user.php">
          <iconify-icon icon="solar:user-plus-rounded-bold-duotone" class="icon"></iconify-icon> Add User
        </a>
      </li>
    <?php } ?>

    <?php if ($_SESSION['role'] === "Admin" || $_SESSION['role'] === "Manufacturer") { ?>
      <li class="nav-item nav-section">Product Management</li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'products.php') ? 'active' : ''; ?>" href="./products.php">
          <iconify-icon icon="solar:box-bold-duotone" class="icon"></iconify-icon> Products
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'add_product.php') ? 'active' : ''; ?>" href="./add_product.php">
          <iconify-icon icon="mdi:plus-box-multiple" class="icon"></iconify-icon> Add Products
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'test_flow_setup.php') ? 'active' : ''; ?>" href="./test_flow_setup.php">
          <iconify-icon icon="solar:slider-minimalistic-horizontal-bold-duotone" class="icon"></iconify-icon> Test Flow Setup
        </a>
      </li>
    <?php } ?>

    <?php if ($_SESSION['role'] === "Admin" || $_SESSION['role'] === "Tester") { ?>
      <li class="nav-item nav-section">Tests Management</li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'test_type.php') ? 'active' : ''; ?>" href="./test_type.php">
          <iconify-icon icon="solar:list-bold-duotone" class="icon"></iconify-icon> Test Type
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'test_entry.php') ? 'active' : ''; ?>" href="./test_entry.php">
          <iconify-icon icon="mdi:clipboard-text" class="icon"></iconify-icon> Test Entry
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage == 'tests.php') ? 'active' : ''; ?>" href="./tests.php">
          <iconify-icon icon="mdi:clipboard-list" class="icon"></iconify-icon> Tests
        </a>
      </li>
    <?php } ?>

  </ul>
</nav>

<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
