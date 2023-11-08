<?php
header("Content-Type:application/json; ");
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
    $query =  "SELECT * FROM BookReturn";
    //Check if there's a request for specific category(id)
    if (isset($_GET['id'])){
        $id = $_GET['id'];
        $query .= " WHERE id = '$id'";
    }
    //Re-Order Query result
    $query .= " ORDER BY return_date DESC";
    //Exec query
    $result = $db->query($query) or die("Error at: " . $db->error);
    //Store data into array
    $return = array();
    while ($row = $result->fetch_assoc()){
        $return[] = $row;
    }
    //Close connection
    $db->close();
    //Return response
    echo json_encode($return, JSON_UNESCAPED_UNICODE);
    die;
}

if ($_SERVER["REQUEST_METHOD"] === 'POST'){
    //Connect to db
    include 'connection.php';
    //Check db connection
    if ($db->connect_error){
        die("Connection failed: " . $db->connect_error);
    }
    if (isset($_POST['add'])){
        $borrow_id = $_POST['borrow_id'];
        //Get Book Borrow
        $query = "SELECT * FROM BookBorrow WHERE id = '$borrow_id'";
        $result = $db->query($query) or die("Error at: " . $db->error);
        $borrow = $result->fetch_assoc();
        //Process the Add
        $currentDate = new DateTime();
        $dueDate = new DateTime($borrow['due_date']);
        $interval = $currentDate->diff($dueDate);
        $daysLate = $interval->format('%a');
        $fine = 0;
        if($currentDate > $dueDate){
            $fine = $daysLate * 5000;
        }
        $return_date = date("Y-m-d");
        $query = "INSERT INTO BookReturn (borrow_id, return_date, fine) VALUES ('$borrow_id', '$return_date', '$fine')";
        $result1 = $db->query($query) or die("Error at: " . $db->error);
        $query = "UPDATE BookBorrow SET returned = 1 WHERE id = '$borrow_id'";
        $result2 = $db->query($query) or die("Error at: " . $db->error);
        //Get book to update avail copy
        $book_id = $borrow['book_id'];
        $query =  "SELECT * FROM Books WHERE id = '$book_id'";
        $result = $db->query($query) or die("Error at: " . $db->error);
        $book = $result->fetch_assoc();
        $new_avail_book = $book['avail_copy'] + 1;
        $query = "UPDATE Books SET avail_copy = '$new_avail_book' WHERE id = '$book_id'";
        $result3 = $db->query($query) or die("Error at: " . $db->error);
        if ($result1 && $result2 && $result3){
            echo json_encode(['status'=>'success']);
        } else {
            echo json_encode(['status'=>'error']);
        }
        $db->close();
    }
    if (isset($_POST['edit'])){
        $id = $_POST['id'];
        $borrow_id = $_POST['borrow_id'];
        //Process old data
        //Get old Return
        $query = "SELECT br.*, bb.id as old_borrow_id, b.id as old_book_id
                    FROM BookReturn br
                    LEFT JOIN BookBorrow bb ON br.borrow_id = bb.id
                    LEFT JOIN Books b ON bb.book_id = b.id
                    WHERE br.id = '$id'";
        $result = $db->query($query) or die("Error at: " . $db->error);
        $old_return = $result->fetch_assoc();
        $old_borrow_id = $old_return['old_borrow_id'];
        $old_book_id = $old_return['old_book_id'];
        //Get new Borrow
        $query = "SELECT bb.*, b.id as book_id
                    FROM BookBorrow bb
                    LEFT JOIN Books b ON bb.book_id = b.id
                    WHERE bb.id = '$borrow_id'";
        $result = $db->query($query) or die("Error at: " . $db->error);
        $borrow = $result->fetch_assoc();
        $borrow_id = $borrow['id'];
        $book_id = $borrow['book_id'];
        //Process new data
        //Get new Info for update
        $currentDate = new DateTime();
        $dueDate = new DateTime($borrow['due_date']);
        $interval = $currentDate->diff($dueDate);
        $daysLate = $interval->format('%a');
        $fine = 0;
        if($currentDate > $dueDate){
            $fine = $daysLate * 5000;
        }
        $return_date = date("Y-m-d");
        //Update Book Return
        $query1 = "UPDATE BookReturn SET borrow_id = '$borrow_id', return_date = '$return_date', fine = '$fine' WHERE id = '$id'";
        $result1 = $db->query($query1) or die("Error at: " . $db->error);
        //Update related data
        $query2 = "UPDATE Books SET avail_copy = avail_copy - 1 WHERE id = '$old_book_id'";
        $result2 = $db->query($query2) or die("Error at: " . $db->error);
        $query3 = "UPDATE BookBorrow SET returned = 0 WHERE id = '$old_borrow_id'";
        $result3 = $db->query($query3) or die("Error at: " . $db->error);
        $query4 = "UPDATE Books SET avail_copy = avail_copy + 1 WHERE id = '$book_id'";
        $result4 = $db->query($query4) or die("Error at: " . $db->error);
        $query5 = "UPDATE BookBorrow SET returned = 1 WHERE id = '$borrow_id'";
        $result5 = $db->query($query5) or die("Error at: " . $db->error);
        if ($result1 && $result2 && $result3 && $result4 && $result5){
            echo json_encode(['status'=>'success']);
        } else {
            echo json_encode(['status'=> 'error']);
        }
        $db->close();
    }
    if (isset($_POST['delete'])){
        $id = $_POST['id'];
        $borrow_id = $_POST['borrow_id'];
        //Get Book Borrow
        $query = "SELECT * FROM BookBorrow WHERE id = '$borrow_id'";
        $result = $db->query($query) or die("Error at: " . $db->error);
        $borrow = $result->fetch_assoc();
        //Process the Delete
        $query = "DELETE FROM BookReturn WHERE id = '$id'";
        //Update borrow status
        $result1 = $db->query($query) or die("Error at: " . $db->error);
        $query = "UPDATE BookBorrow SET returned = 0 WHERE id = '$borrow_id'";
        $result2 = $db->query($query) or die("Error at: " . $db->error);
        //Get book to update avail copy
        $book_id = $borrow['book_id'];
        $query =  "SELECT * FROM Books WHERE id = '$book_id'";
        $result = $db->query($query) or die("Error at: " . $db->error);
        $book = $result->fetch_assoc();
        $new_avail_book = $book['avail_copy'] - 1;
        $query = "UPDATE Books SET avail_copy = '$new_avail_book' WHERE id = '$book_id'";
        $result3 = $db->query($query) or die("Error at: " . $db->error);
        if ($result1 && $result2 && $result3){
            echo json_encode(['status'=>'success']);
        } else {
            echo json_encode(['status'=>'error']);
        }
        $db->close();
    }
}
?>