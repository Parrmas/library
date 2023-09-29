<?php
    header("Content-Type:application/json;");
    header("Access-Control-Allow-Origin: *");
    //Handle GET method
    if ($_SERVER['REQUEST_METHOD'] === 'GET'){
        //Connect to db
        include 'connection.php';
        //Check db connection
        if ($db->connect_error){
            die("Connection failed: " . $db->connect_error);
        }
        //Build query
        $query =   "SELECT *
                    FROM Employees";
        //Check if there's a request for specific employee(id)
        if (isset($_GET['id'])){
            $id = $_GET['id'];
            $query .= " WHERE id = '$id'";
        }
        //Re-Order Query result
        $query .= " ORDER BY name ASC";
        //Exec query
        $result = $db->query($query) or die("Error at: " . $db->error);
        //Store data into array
        $employees = array();
        while ($row = $result->fetch_assoc()){
            $employees[] = $row;
        }
        //Close connection
        $db->close();
        //Return response
        echo json_encode($employees, JSON_UNESCAPED_UNICODE);
    }
    //Handle POST method
    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        //Connect to db
        include 'connection.php';
        //Check db connection
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        if (isset($_POST['add'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $phone = $_POST['phone'];
            $query = "INSERT INTO Employees (name, email, password, phone) VALUES ('$name', '$email', '$password', '$phone')";
            $result = $db->query($query) or die("Error at: " . $db->error);
            $db->close();
        }
        if (isset($_POST['edit'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $phone = $_POST['phone'];
            $query = "UPDATE Employees SET name = '$name', email = '$email', password = '$password', phone = '$phone' WHERE id = '$id'";
            $result = $db->query($query) or die("Error at: " . $db->error);
            $db->close();
        }
        if (isset($_POST['delete'])) {
            $id = $_POST['id'];
            $query = "DELETE FROM Employees WHERE id = '$id'";
            $result = $db->query($query) or die("Error at: " . $db->error);
            $db->close();
        }
        header('Location:../employees_admin_view.php');
    }
?>
