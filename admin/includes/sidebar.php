<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
        <span class="brand-text font-weight-light">Studio Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block"><?= $_SESSION['admin']['HoTen'] ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="index.php" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="booking-list.php" class="nav-link">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>Quản lý đặt lịch</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="service-list.php" class="nav-link">
                        <i class="nav-icon fas fa-camera"></i>
                        <p>Quản lý dịch vụ</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="gallery-list.php" class="nav-link">
                        <i class="nav-icon fas fa-images"></i>
                        <p>Quản lý bộ sưu tập</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="user-list.php" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Quản lý người dùng</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
