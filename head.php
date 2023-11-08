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

        .nav-link {
            font-size: 22px;
            color: white;
            font-weight: bold;
            transition: opacity 0.3s;
            opacity: 1;
        }

        .custom-navbar-bg {
            background-color: #500050;
        }

        .nav-link-separator {
            border: 2px solid white; /* Add a horizontal line as separator */
            margin-top: 10px; /* Adjust the spacing as needed */
            margin-left: 10px;
            margin-right: 10px;
            border-radius: 10px;
        }
        .form-floating > .date > .form-control {
             padding: 1rem 0.75rem;
         }
        .form-floating > .date > .form-control {
            height: calc(3.5rem + 2px);
            line-height: 1.25;
        }
        .form-floating .select2-container--bootstrap-5 .select2-selection {
            height: calc(3.5rem + 2px);
            padding: 1rem 0.75rem;
        }

        .form-floating .select2-container--bootstrap-5 .select2-selection>.select2-selection__rendered {
            margin-top: 0.6rem;
            margin-left: 0.25rem;
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
                <li><a class="dropdown-item" href="api/api_admin_logout.php">Đăng xuất</a></li>
            </ul>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark custom-navbar-bg" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
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
                    <div class="nav-link-separator"></div> <!-- Separator line -->
                    <a class="nav-link" href="readers_admin_view.php">
                        <i class="fas fa-book-reader" style="margin-right: 10px;"></i>
                        Quản lý độc giả
                    </a>
                    <div class="nav-link-separator"></div> <!-- Separator line -->
                    <a class="nav-link" href="borrow_admin_view.php">
                        <i class="fa-solid fa-share" style="margin-right: 10px"></i>
                        Quản lý mượn sách
                    </a>
                    <div class="nav-link-separator"></div> <!-- Separator line -->
                    <a class="nav-link" href="return_admin_view.php">
                        <i class="fa-solid fa-reply-all" style="margin-right: 10px"></i>
                        Quản lý trả sách
                    </a>
                </div>
            </div>

            <?php if(isset($_SESSION['username'])): ?>
                <div class="sb-sidenav-footer" style="background-color: #500050;">
                    <div class="small" style="color: white">Đã đăng nhập với:</div>
                    <div class="small" style="color: white"><?= $_SESSION['username']; ?></div>
                </div>
            <?php endif; ?>
        </nav>
    </div>
    <div id="layoutSidenav_content">

