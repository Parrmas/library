<?php
    header("Content-Type:application/json;");
    header("Access-Control-Allow-Origin: *");
    //Get method for getting books
    if ($_SERVER['REQUEST_METHOD'] === 'GET'){
        //Connect to db
        include 'connection.php';
        //Check db connection
        if ($db->connect_error){
            die("Connection failed: " . $db->connect_error);
        }
        //Build query
        $query =   "SELECT *
                    FROM BookCategories";
        //Check if there's a request for specific category(id)
        if (isset($_GET['id'])){
            $id = $_GET['id'];
            $query .= " WHERE id = '$id'";
        }
        //Re-Order Query result
        $query .= " ORDER BY name ASC";
        //Exec query
        $result = $db->query($query) or die("Error at: " . $db->error);
        //Store data into array
        $categories = array();
        while ($row = $result->fetch_assoc()){
            $categories[] = $row;
        }
        //Close connection
        $db->close();
        //Return response
        echo json_encode($categories, JSON_UNESCAPED_UNICODE);
    }

    if ($_SERVER["REQUEST_METHOD"] === 'POST'){
        //Connect to db
        include 'connection.php';
        //Check db connection
        if ($db->connect_error){
            die("Connection failed: " . $db->connect_error);
        }
        if (isset($_POST['add'])){
            $name = $_POST['name'];
            $query = "INSERT INTO BookCategories (name) VALUES ('$name')";
            $result = $db->query($query) or die("Error at: " . $db->error);
            if ($result) {
                echo json_encode(['status'=>'success']);
            } else {
                echo json_encode(['status'=>'error']);
            }
            $db->close();
        }
        if (isset($_POST['edit'])){
            $id = $_POST['id'];
            $name = $_POST['name'];
            $query = "UPDATE BookCategories SET name = '$name' WHERE id = '$id'";
            $result = $db->query($query) or die("Error at: " . $db->error);
            if ($result) {
                echo json_encode(['status'=>'success']);
            } else {
                echo json_encode(['status'=>'error']);
            }
            $db->close();
        }
        if (isset($_POST['delete'])){
            $id = $_POST['id'];
            $query = "DELETE FROM BookCategories WHERE id = '$id'";
            $result = $db->query($query) or die("Error at: " . $db->error);
            if ($result) {
                echo json_encode(['status'=>'success']);
            } else {
                echo json_encode(['status'=>'error']);
            }
            $db->close();
        }
    }
?>