<?php
header("Content-Type:application/json");
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    //Connect to db
    include 'connection.php';
    //Process analytics type
    //Choose the query type month or date
    if ($_POST['selectQuery'] === 'date'){
        $query = "SELECT bb.borrow_date, COUNT(bb.id) as num_borrow, COALESCE(SUM(br.fine),0) as total_fine
                    FROM BookBorrow bb 
                    LEFT JOIN BookReturn br ON bb.id = br.borrow_id";
        $groupBy = " GROUP BY bb.borrow_date";
    }
    if ($_POST['selectQuery'] === 'month'){
        $query = "SELECT YEAR(bb.borrow_date) as year, MONTH(bb.borrow_date) as month,COUNT(bb.id) as num_borrow, COALESCE(SUM(br.fine),0) as total_fine
                    FROM BookBorrow bb 
                    LEFT JOIN BookReturn br ON bb.id = br.borrow_id";
        $groupBy = " GROUP BY YEAR(bb.borrow_date), MONTH(bb.borrow_date)";
    }
    //Process if query has specific month
    if (isset($_POST['monthQuery']) && $_POST['monthQuery'] !== ''){
        $query .= " WHERE MONTH(bb.borrow_date) = " . $_POST['monthQuery'];
    }
    //Complete the grouping
    $query .= $groupBy;
    //Process if query has amount ordering
    if ($_POST['amountOrder'] === 'asc'){
        $query .= " ORDER BY COUNT(bb.id) ASC";
    } else if ($_POST['amountOrder'] === 'desc'){
        $query .= " ORDER BY COUNT(bb.id) DESC";
    } else {
        $query .= " ORDER BY bb.borrow_date DESC";
    }
    $result = $db->query($query) or die("Error at: " . $db->error);
    if ($result){
        $data = array();
        while ($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['status'=>'failed']);
    }
}
?>

