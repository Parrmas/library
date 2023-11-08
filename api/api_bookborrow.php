<?php
header("Content-Type:application/json;");
header("Access-Control-Allow-Origin: *");
//Get method for getting bookborrows
if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    //Connect to db
    include 'connection.php';
    //Check db connection
    if ($db->connect_error){
        die("Connection failed: " . $db->connect_error);
    }
    //Build query
    $query =   "SELECT bb.*, b.name as book_name, b.category_id as book_category, r.name as reader_name, r.email as reader_email 
                FROM BookBorrow bb
                LEFT JOIN Books b ON bb.book_id = b.id
                LEFT JOIN Readers r ON bb.reader_id = r.id";
    //Check if there's a request for specific category(id)
    if (isset($_GET['id'])){
        $id = $_GET['id'];
        $query .= " WHERE bb.id = '$id'";
    }
    //Re-Order Query result
    $query .= " ORDER BY borrow_date DESC";
    //Exec query
    $result = $db->query($query) or die("Error at: " . $db->error);
    //Store data into array
    $borrow = array();
    while ($row = $result->fetch_assoc()){
        $borrow[] = $row;
    }
    //Close connection
    $db->close();
    //Return response
    echo json_encode($borrow, JSON_UNESCAPED_UNICODE);
}

if ($_SERVER["REQUEST_METHOD"] === 'POST'){
    //Connect to db
    include 'connection.php';
    //Check db connection
    if ($db->connect_error){
        die("Connection failed: " . $db->connect_error);
    }
    if (isset($_POST['add'])){
        $reader_id = $_POST['reader_id'];
        $query = "SELECT * FROM BookBorrow WHERE reader_id = '$reader_id' AND returned = 0";
        $result = $db->query($query) or die("Error at: " . $db->error);
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        if (count($rows) >= 3) {
            echo json_encode(['status'=>'overload']);
            exit;
        }
        $book_id = $_POST['book_id'];
        $borrow_date = date("Y-m-d");
        $due_date = date_create_from_format("d/m/Y", $_POST['due_date']);
        $formatted_due_date = date_format($due_date, "Y-m-d");
        $returned = 0; //0 as false; 1 as true
        $query = "INSERT INTO BookBorrow (reader_id, book_id, borrow_date, due_date, returned) VALUES ('$reader_id', '$book_id', '$borrow_date', '$formatted_due_date', '$returned')";
        $result1 = $db->query($query) or die("Error at: " . $db->error);
        //Get book to update avail copy
        $query =  "SELECT * FROM Books WHERE id = '$book_id'";
        $result = $db->query($query) or die("Error at: " . $db->error);
        $book = $result->fetch_assoc();
        $new_avail_book = $book['avail_copy'] - 1;
        $query = "UPDATE Books SET avail_copy = '$new_avail_book' WHERE id = $book_id";
        $result2 = $db->query($query) or die("Error at: " . $db->error);
        if ($result1 && $result2){
            echo json_encode(['status'=>'success']);
        } else {
            echo json_encode(['status'=>'error']);
        }
        $db->close();
    }
    if (isset($_POST['edit'])){
        $id = $_POST['id'];
        $reader_id = $_POST['reader_id'];
        $book_id = $_POST['book_id'];
        $due_date = date_create_from_format("d/m/Y", $_POST['due_date']);
        $formatted_due_date = date_format($due_date, "Y-m-d");
        //GET Borrow info
        $query = "SELECT * FROM BookBorrow WHERE id = '$id'";
        $result = $db->query($query) or die("Error at: " . $db->error);
        $borrow = $result->fetch_assoc();
        //Get old book
        $old_book_id = $borrow['book_id'];
        //Update old book avail copy
        //Get book to update avail copy
        $query =  "SELECT * FROM Books WHERE id = '$old_book_id'";
        $result = $db->query($query) or die("Error at: " . $db->error);
        $book = $result->fetch_assoc();
        $new_avail_book = $book['avail_copy'] + 1;
        $query = "UPDATE Books SET avail_copy = '$new_avail_book' WHERE id = $old_book_id";
        $result2 = $db->query($query) or die("Error at: " . $db->error);
        //Update Borrow
        $query = "UPDATE BookBorrow SET reader_id = '$reader_id', book_id = '$book_id', due_date ='$formatted_due_date' WHERE id = '$id'";
        $result = $db->query($query) or die("Error at: " . $db->error);
        //Update new book avail copy
        //Get book to update avail copy
        $query =  "SELECT * FROM Books WHERE id = '$book_id'";
        $result = $db->query($query) or die("Error at: " . $db->error);
        $book = $result->fetch_assoc();
        $new_avail_book = $book['avail_copy'] - 1;
        $query = "UPDATE Books SET avail_copy = '$new_avail_book' WHERE id = $book_id";
        $result2 = $db->query($query) or die("Error at: " . $db->error);
        if ($result){
            echo json_encode(['status'=>'success']);
        } else {
            echo json_encode(['status'=>'error']);
        }
        $db->close();
    }
    if (isset($_POST['delete'])){
        $id = $_POST['id'];
        $book_id = $_POST['book_id'];
        $query = "DELETE FROM BookBorrow WHERE id = '$id'";
        $result1 = $db->query($query) or die("Error at: " . $db->error);
        //Get book to update avail copy
        $query =  "SELECT * FROM Books WHERE id = '$book_id'";
        $result = $db->query($query) or die("Error at: " . $db->error);
        $book = $result->fetch_assoc();
        $new_avail_book = $book['avail_copy'] + 1;
        $query = "UPDATE Books SET avail_copy = '$new_avail_book' WHERE id = $book_id";
        $result2 = $db->query($query) or die("Error at: " . $db->error);
        if ($result1 && $result2){
            echo json_encode(['status'=>'success']);
        } else {
            echo json_encode(['status'=>'error']);
        }
        $db->close();
    }
}
?>

