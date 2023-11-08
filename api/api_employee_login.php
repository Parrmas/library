<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");

session_start();

// Check if the login form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    include 'connection.php';
    //Check connection
    if ($db->connect_error) {
        die ("Connection failed: " . $db->connect_error);
    }

    $query = "SELECT * FROM Employees WHERE email = '$email'";
    $result = mysqli_query($db, $query) or die(mysqli_error($db));

    if ($result->num_rows === 1) {
        $row = mysqli_fetch_array($result);
        $login_email = $row['email'];
        $login_password = $row['password'];

        // Validate the login credentials (you'll need to replace this with your own validation logic)
        if ($email === $login_email && $password === $login_password) {
            // Set the admin's session information
            $_SESSION['email'] = $login_email;
            echo json_encode(['message' => 'Đăng nhập thành công', 'status' => 0]);
            exit();
        } else {
            echo json_encode(['message' => 'Sai email hoặc mật khẩu', 'status' => 1]);
        }
    }
    $db->close();
}
?>

