<?php
    // Check if the admin is logged in
    session_start();

    $_SESSION['admin'] = false;
    $_SESSION['username'] = '';

    header('Location: ../admin_login.php');
    exit();
?>
