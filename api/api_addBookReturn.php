<?php
header("Content-Type:application/json;");
header("Access-Control-Allow-Origin: *");
//Check for POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Connect to db
    include 'connection.php';
    //Check db connection
    if ($db->connect_error) {
        http_response_code(500);
        echo json_encode(['message' => 'Kết nối database thất bại, Error at: ' . $db->error, 'status' => 0]);
    }
    //Check if POST values are empty
    if (isset($_POST['borrow_id']) && $_POST['borrow_id'] !== ''){
        $borrow_id = $_POST['borrow_id'];
    } else {
        echo json_encode(['message' => 'Không tìm thấy biến borrow_id', 'status' => 1]);
        exit();
    }
    //Get the Borrow Due date
    //Prepare Statement Query
    $stmt = $db->prepare("SELECT due_date FROM BookBorrow WHERE id = ?");
    if ($stmt === false) {
        // Handle statement preparation error
        http_response_code(500);
        echo json_encode(['message' => 'Chuẩn bị statement thất bại (lỗi kết nối database): ' . $db->error, 'status' => 2]);
        exit;
    }
    $stmt->bind_param('i', $borrow_id);
    //Execute Query
    $result = $stmt->execute();

    if ($result) {
        $result = $stmt->get_result();
        $due_date = strtotime($result['due_date']);
        $return_date = date('d/m/Y');
        $return_date_str = strtotime($return_date);
        $diff = date_diff($due_date, $return_date_str)->days;
        $fine = 5000 * $diff;
        //Add the Return
        //Prepare Insert Statement Query
        $stmt = $db->prepare("INSERT INTO BookReturn (borrow_id, return_date, fine) VALUES (?,?,?)");
        if ($stmt === false) {
            // Handle statement preparation error
            http_response_code(500);
            echo json_encode(['message' => 'Chuẩn bị statement thất bại (lỗi kết nối database): ' . $db->error, 'status' => 3]);
            exit;
        }
        $borrow_date = date('d/m/Y');
        $returned = 0; //0 for false, 1 for true
        $stmt->bind_param('isi', $borrow_id, $return_date, $fine);
        //Execute Query
        $result = $stmt->execute();
        //Check if Query executed successfully
        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => 'Lập phiếu trả sách thành công', 'status' => 4]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Lập phiếu trả sách thất bại, Error at: ' . $stmt->error, 'status' => 5]);
        }
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Tìm thông tin phiếu mượn sách thất bại, Error at: ' . $stmt->error, 'status' => 6]);
    }
    $db->close();
}
?>
