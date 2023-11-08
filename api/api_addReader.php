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
        exit;
    }
    //Check if POST values are empty
    if (isset($_POST['name']) && $_POST['name'] !== ''){
        $name = $_POST['name'];
    } else {
        echo json_encode(['message' => 'Không tìm thấy biến name', 'status' => 1]);
        exit;
    }
    if (isset($_POST['email']) && $_POST['email'] !== ''){
        $email = $_POST['email'];
    } else {
        echo json_encode(['message' => 'Không tìm thấy biến email', 'status' => 2]);
        exit;
    }
    if (isset($_POST['phone']) && $_POST['phone'] !== ''){
        $phone = $_POST['phone'];
    } else {
        echo json_encode(['message' => 'Không tìm thấy biến phone', 'status' => 3]);
        exit;
    }
    //Check if there's a Reader with this email
    //Prepare Statement Query
    $stmt = $db->prepare("SELECT * FROM Readers WHERE email = ?");
    if ($stmt === false) {
        // Handle statement preparation error
        http_response_code(500);
        echo json_encode(['message' => 'Chuẩn bị statement thất bại (lỗi kết nối database): ' . $db->error, 'status' => 4]);
        exit;
    }
    $stmt->bind_param('s', $email);
    //Execute Query
    $result = $stmt->execute();
    if ($result){
        $result = $stmt->get_result();
        if ($result->num_rows !== 0){
            echo json_encode(['message' => 'Đã có độc giả với email này', 'status' => 5]);
            exit;
        } else {
            //Prepare Statement Query
            $stmt = $db->prepare("INSERT INTO Readers (name, email, phone) VALUES (?,?,?)");
            if ($stmt === false) {
                // Handle statement preparation error
                http_response_code(500);
                echo json_encode(['message' => 'Chuẩn bị statement thất bại (lỗi kết nối database): ' . $db->error, 'status' => 6]);
                exit;
            }
            $stmt->bind_param('sss', $name, $email, $phone);
            //Execute Query
            $result = $stmt->execute();
            //Check if Query executed successfully
            if ($result) {
                http_response_code(200);
                echo json_encode(['message' => 'Thêm độc giả thành công', 'status' => 7]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Thêm độc giả thất bại, Error at: ' . $stmt->error, 'status' => 8]);
                exit;
            }
        }
    }
    $db->close();
}
?>
