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
                FROM BooksCategories";
    //Check if there's a request for specific book(id)
    if (isset($_GET['id'])){
        $id = $_GET['id'];
        $query .= " WHERE b.id = '$id'";
    }
    //Re-Order Query result
    $query .= " ORDER BY b.name ASC";
    //Exec query
    $result = $db->query($query) or die("Error at: " . $db->error);
    //Store data into array
    $category = array();
    while ($row = $result->fetch_assoc()){
        $category[] = $row;
    }
    //Close connection
    $db->close();
    //Return response
    echo json_encode($category, JSON_UNESCAPED_UNICODE);
}
?>

