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
    <script src="js/datepicker.js"></script>
    <style>
        /* Custom CSS for the navbar brand */
        .navbar-brand-custom {
            display: flex;
            align-items: center;
            font-size: 30px;
        }

        .custom-navbar-bg {
            background-color: #000544;
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
                <li><a class="dropdown-item" href="api/api_employeeLogout.php">Đăng xuất</a></li>
            </ul>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark custom-navbar-bg" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <a class="nav-link" href="books_employee_view.php">
                        <i class="fas fa-book-open" style="margin-right: 10px;"></i>
                        Danh sách sách
                    </a>
<!--                    <div class="nav-link-separator"></div> <!-- Separator line -->
                    <a class="nav-link" href="addReader_employee_view.php">
                        <i class="fa-solid fa-plus" style="margin-right: 10px;"></i>
                        Thêm độc giả
                    </a>
<!--                    <div class="nav-link-separator"></div> <!-- Separator line -->
                    <a class="nav-link" href="listReader_employee_view.php">
                        <i class="fas fa-book-reader" style="margin-right: 10px;"></i>
                        Danh sách độc giả
                    </a>
                    <!--                    <div class="nav-link-separator"></div> <!-- Separator line -->
                    <a class="nav-link" href="addBorrow_employee_view.php">
                        <i class="fa-solid fa-plus" style="margin-right: 10px;"></i>
                        Lập phiếu mượn sách
                    </a>
                    <!--                    <div class="nav-link-separator"></div> <!-- Separator line -->
                    <a class="nav-link" href="listBorrow_employee_view.php">
                        <i class="fas fa-share" style="margin-right: 10px;"></i>
                        Danh sách phiếu mượn sách
                    </a>
                    <!--                    <div class="nav-link-separator"></div> <!-- Separator line -->
                    <a class="nav-link" href="addReturn_employee_view.php">
                        <i class="fas fa-plus" style="margin-right: 10px;"></i>
                        Lập phiếu trả sách
                    </a>
                    <!--                    <div class="nav-link-separator"></div> <!-- Separator line -->
                    <a class="nav-link" href="listReturn_employee_view.php">
                        <i class="fas fa-reply-all" style="margin-right: 10px;"></i>
                        Danh sách phiếu trả sách
                    </a>
                </div>
            </div>

            <?php if(isset($_SESSION['email'])): ?>
                <div class="sb-sidenav-footer" style="background-color: #000544">
                    <div class="small" style="color: white">Đã đăng nhập với:</div>
                    <div class="small" style="color: white"><?= $_SESSION['email']; ?></div>
                </div>
            <?php endif; ?>
        </nav>
    </div>
    <div id="layoutSidenav_content">

