<?php
header("Content-Type:application/json;");
header("Access-Control-Allow-Origin: *");
//Get method for getting bookborrows
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //Connect to db
    include 'connection.php';
    //Check db connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    $query = "SELECT bb.*, b.name as book_name, b.category_id as book_category, r.name as reader_name, r.email as reader_email 
                FROM BookBorrow bb
                LEFT JOIN Books b ON bb.book_id = b.id
                LEFT JOIN Readers r ON bb.reader_id = r.id
                WHERE bb.returned = 0";
    $query .= " ORDER BY borrow_date DESC";
    //Exec query
    $result = $db->query($query) or die("Error at: " . $db->error);
    //Store data into array
    $borrow = array();
    while ($row = $result->fetch_assoc()) {
        $borrow[] = $row;
    }
    //Close connection
    $db->close();
    //Return response
    echo json_encode($borrow, JSON_UNESCAPED_UNICODE);
}