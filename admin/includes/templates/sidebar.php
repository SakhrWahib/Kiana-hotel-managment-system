<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="layout/images/faces/face1.jpg" alt="profile">
          <span class="login-status online"></span>
        </div>
        <div class="nav-profile-text d-flex flex-column">
          <span class="font-weight-bold mb-2"><?php echo $_SESSION['username']; ?></span>
          <span class="text-secondary text-small"><?php echo $_SESSION['role']; ?></span>
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="dashboard.php">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>
    <?php
    if ($_SESSION['role'] == 'admin') {
    ?>
      <li class="nav-item">
        <a class="nav-link" href="admins.php">
          <span class="menu-title">Admins</span>
          <i class="mdi mdi-account-key menu-icon"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="departments.php">
          <span class="menu-title">Departments</span>
          <i class="mdi mdi-vector-triangle menu-icon"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="employees.php">
          <span class="menu-title">Employees</span>
          <i class="mdi mdi-worker menu-icon"></i>
        </a>
      </li>
    <?php } ?>
    <li class="nav-item">
      <a class="nav-link" href="guests.php">
        <span class="menu-title">Guests</span>
        <i class="mdi mdi-account menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="books.php">
        <span class="menu-title">Books</span>
        <i class="mdi mdi-bookmark menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="rooms.php">
        <span class="menu-title">Rooms</span>
        <i class="mdi mdi-seat-flat menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="room_type.php">
        <span class="menu-title">Room Type</span>
        <i class="mdi mdi-seat-individual-suite menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="orders.php">
        <span class="menu-title">Orders</span>
        <i class="mdi mdi-format-list-bulleted menu-icon"></i>
      </a>
    </li>
  </ul>
</nav>