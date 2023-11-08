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
    //Check Query Parameter
    if (isset($_GET['id']) && $_GET['id'] !== '') {
        //Get Query Parameter
        $id = $_GET['id'];
        //Get data from db
        //Prepare Statement Query
        $stmt = $db->prepare("SELECT b.*, bc.name as category_name 
                                        FROM Books b
                                        LEFT JOIN BookCategories bc ON b.category_id = bc.id
                                        WHERE b.id = ?");
        if ($stmt === false) {
            // Handle statement preparation error
            http_response_code(500);
            echo json_encode(['message' => 'Chuẩn bị statement thất bại (lỗi kết nối database): ' . $db->error, 'status' => 1]);
            exit;
        }
        $stmt->bind_param("i", $id);
        //Execute Query
        $result = $stmt->execute();
        //Check if Query executed successfully
        if ($result){
            $result = $stmt->get_result();
            //Check if there's data from result
            if ($result->num_rows !== 0) {
                $book = $result->fetch_assoc();
                http_response_code(200);
                echo json_encode($book,JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['message' => 'Không có sách với id này này', 'status' => 2]);
            }
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Chạy query thất bại, lấy dữ liệu thất bại. Error at: ' . $stmt->error, 'status' => 3]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Không tìm thấy biến nhập vào: id', 'status' => 4]);
    }
}
?>
