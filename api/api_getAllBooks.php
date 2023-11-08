<?php
header("Content-Type:application/json;");
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //Connect to db
    include 'connection.php';
    //Check db connection
    if ($db->connect_error) {
        http_response_code(500);
        echo json_encode(['message' => 'Kết nối database thất bại, Error at: ' . $db->error, 'status' => 0]);
    }
    //Get data from db
    //Prepare Statement Query
    $stmt = $db->prepare("SELECT * FROM Books");
    if ($stmt === false) {
        // Handle statement preparation error
        http_response_code(500);
        echo json_encode(['message' => 'Chuẩn bị statement thất bại (lỗi kết nối database): ' . $db->error, 'status' => 1]);
        exit;
    }
    //Execute Query
    $result = $stmt->execute();
    //Check if Query executed successfully
    if ($result){
        $result = $stmt->get_result();
        //Check if there's data from result
        if ($result->num_rows !== 0) {
            $books = array();
            while($row = $result->fetch_assoc()){
                $books[] = $row;
            }
            http_response_code(200);
            echo json_encode($books);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Không có sách', 'status' => 2]);
        }
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Chạy query thất bại, lấy dữ liệu thất bại. Error at: ' . $stmt->error, 'status' => 3]);
    }
}
?>
