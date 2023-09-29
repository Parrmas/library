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
                        FROM Readers";
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
        $readers = array();
        while ($row = $result->fetch_assoc()){
            $readers[] = $row;
        }
        //Close connection
        $db->close();
        //Return response
        echo json_encode($readers, JSON_UNESCAPED_UNICODE);
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
            $phone = $_POST['phone'];
            $query = "INSERT INTO Readers (name, email, phone) VALUES ('$name', '$email', '$phone')";
            $result = $db->query($query) or die("Error at: " . $db->error);
            $db->close();
        }
        if (isset($_POST['edit'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $query = "UPDATE Readers SET name = '$name', email = '$email', phone = '$phone' WHERE id = '$id'";
            $result = $db->query($query) or die("Error at: " . $db->error);
            $db->close();
        }
        if (isset($_POST['delete'])) {
            $id = $_POST['id'];
            $query = "DELETE FROM Readers WHERE id = '$id'";
            $result = $db->query($query) or die("Error at: " . $db->error);
            $db->close();
        }
        header('Location:../employees_admin_view.php');
    }
?>
