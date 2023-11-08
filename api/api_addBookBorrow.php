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
    if (isset($_POST['reader_id']) && $_POST['reader_id'] !== ''){
        $reader_id = $_POST['reader_id'];
    } else {
        echo json_encode(['message' => 'Không tìm thấy biến reader_id', 'status' => 1]);
        exit();
    }
    if (isset($_POST['book_id']) && $_POST['book_id'] !== ''){
        $book_id = $_POST['book_id'];
    } else {
        echo json_encode(['message' => 'Không tìm thấy biến book_id', 'status' => 2]);
        exit();
    }
    if (isset($_POST['due_date']) && $_POST['due_date'] !== ''){
        $due_date = $_POST['due_date'];
    } else {
        echo json_encode(['message' => 'Không tìm thấy biến due_date', 'status' => 3]);
        exit();
    }
    //Check if user has borrowed more than 3 books
    //Prepare Statement Query
    $stmt = $db->prepare("SELECT COUNT(id) as amount FROM BookBorrow WHERE reader_id = ?");
    if ($stmt === false) {
        // Handle statement preparation error
        http_response_code(500);
        echo json_encode(['message' => 'Chuẩn bị statement thất bại (lỗi kết nối database): ' . $db->error, 'status' => 4]);
        exit;
    }
    $stmt->bind_param('i', $reader_id);
    //Execute Query
    $result = $stmt->execute();

    if ($result) {
        $result = $stmt->get_result();
        if ($result['amount'] > 3){
            echo json_encode(['message' => 'Độc giả đã mượn 3 cuốn sách. Không được mượn thêm!', 'status' => 5]);
            exit;
        } else {
            //Add the Borrow
            //Prepare Insert Statement Query
            $stmt = $db->prepare("INSERT INTO BookBorrow (reader_id, book_id, borrow_date, due_date, returned) VALUES (?,?,?,?,?)");
            if ($stmt === false) {
                // Handle statement preparation error
                http_response_code(500);
                echo json_encode(['message' => 'Chuẩn bị statement thất bại (lỗi kết nối database): ' . $db->error, 'status' => 6]);
                exit;
            }
            $borrow_date = date('d/m/Y');
            $returned = 0; //0 for false, 1 for true
            $stmt->bind_param('iissi', $reader_id, $book_id, $borrow_date, $due_date, $returned);
            //Execute Query
            $result = $stmt->execute();
            //Check if Query executed successfully
            if ($result) {
                http_response_code(200);
                echo json_encode(['message' => 'Lập phiếu mượn sách thành công', 'status' => 7]);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Lập phiếu mượn sách thất bại, Error at: ' . $stmt->error, 'status' => 8]);
            }
        }
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Kiểm tra số sách độc giả đang mượn thất bại, Error at: ' . $stmt->error, 'status' => 9]);
    }
}
?>
