<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Quản lý Thư viện</title>
    <link href="css/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="js/fontawesome.js" crossorigin="anonymous"></script>
    <script src="js/jquery-3.7.0.min.js"></script>
    <style>
        /* Custom CSS for the navbar brand */
        .navbar-brand-custom {
            display: flex;
            align-items: center;
            font-size: 30px;
        }

        .nav-link {
            font-size: 22px;
            color: white;
            font-weight: bold;
            transition: opacity 0.3s;
            opacity: 1;
        }

        .custom-navbar-bg {
            background-color: #a64dff;
        }

        .nav-link-separator {
            border: 3px solid white; /* Add a horizontal line as separator */
            margin-top: 10px; /* Adjust the spacing as needed */
            margin-left: 10px;
            margin-right: 10px;
            border-radius: 10px;
        }

    </style>
</head>
<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-dark custom-navbar-bg">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3 navbar-brand-custom">Pạt's Lib <i class="fas fa-book" style="margin-left: 20px"></i></a>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" data-bs-target="#myDropdown"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" id="myDropdown" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="api/api_logout.php">Đăng xuất</a></li>
            </ul>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark custom-navbar-bg" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="nav-link-separator"></div> <!-- Separator line -->
                    <a class="nav-link" href="analytics_admin_view.php">
                        <i class="fas fa-chart-bar" style="margin-right: 10px;"></i>
                        Thống kê
                    </a>
                    <div class="nav-link-separator"></div> <!-- Separator line -->
                    <a class="nav-link" href="books_admin_view.php">
                        <i class="fas fa-book-open" style="margin-right: 10px;"></i>
                        Quản lý sách
                    </a>
                    <div class="nav-link-separator"></div> <!-- Separator line -->
                    <a class="nav-link" href="categories_admin_view.php">
                        <i class="fas fa-list-alt" style="margin-right: 10px;"></i>
                        Quản lý chủ đề
                    </a>
                    <div class="nav-link-separator"></div> <!-- Separator line -->
                    <a class="nav-link" href="employees_admin_view.php">
                        <i class="fas fa-user" style="margin-right: 10px;"></i>
                        Quản lý nhân viên
                    </a>
                </div>
            </div>

            <?php if(isset($_SESSION['username'])): ?>
                <div class="sb-sidenav-footer" style="background-color: #a64dff;">
                    <div class="small" style="color: white">Đã đăng nhập với:</div>
                    <div class="small" style="color: white"><?= $_SESSION['username']; ?></div>
                </div>
            <?php endif; ?>
        </nav>
    </div>
    <div id="layoutSidenav_content">

