<nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
              <span class="hide-menu">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./dashboard.php" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6"></iconify-icon>
                </span>
                <span class="hide-menu">Dashboard</span> 
              </a>
            </li>
            <?php 
            if($_SESSION['role']==="Admin"){
              ?>
            
            <li class="nav-small-cap">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-6" class="fs-6"></iconify-icon>
              <span class="hide-menu">User Management</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./users.php" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:user-plus-rounded-bold-duotone" class="fs-6"></iconify-icon>
                </span>
                <span class="hide-menu">Users</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./add_user.php" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:user-plus-rounded-bold-duotone" class="fs-6"></iconify-icon>
                </span>
                <span class="hide-menu">Add User</span> 
              </a>
            </li>
            <?php
          }
          ?>
          <?php
          if($_SESSION['role']==="Admin"|| $_SESSION['role']==="Manufacturer"){
            ?>
           <li class="nav-small-cap">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-6" class="fs-6"></iconify-icon>
              <span class="hide-menu">product Management</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./products.php" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:user-plus-rounded-bold-duotone" class="fs-6"></iconify-icon>
                </span>
                <span class="hide-menu">products</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./add_product.php" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:user-plus-rounded-bold-duotone" class="fs-6"></iconify-icon>
                </span>
                <span class="hide-menu">Add products</span> 
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./test_flow_setup.php" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:user-plus-rounded-bold-duotone" class="fs-6"></iconify-icon>
                </span>
                <span class="hide-menu">Test Flow Setup</span> 
              </a>
            </li>
            <?php
             }
             ?>
            <?php
            if($_SESSION['role']==="Admin" || $_SESSION ['role']==="Tester"){
              ?>
             <li class="nav-small-cap">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-6" class="fs-6"></iconify-icon>
              <span class="hide-menu">Tests Management</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./test_type.php" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:user-plus-rounded-bold-duotone" class="fs-6"></iconify-icon>
                </span>
                <span class="hide-menu">Test Type</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./test_entry.php" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:user-plus-rounded-bold-duotone" class="fs-6"></iconify-icon>
                </span>
                <span class="hide-menu">Test Entry</span> 
              </a>
            </li>
            <?php
            }
            ?>
          </ul>
        </nav>