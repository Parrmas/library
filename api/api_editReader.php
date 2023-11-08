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
    if (isset($_POST['id']) && $_POST['id'] !== ''){
        $id = $_POST['id'];
    } else {
        echo json_encode(['message' => 'Không tìm thấy biến id', 'status' => 1]);
        exit;
    }
    if (isset($_POST['name']) && $_POST['name'] !== ''){
        $name = $_POST['name'];
    } else {
        echo json_encode(['message' => 'Không tìm thấy biến name', 'status' => 2]);
        exit;
    }
    if (isset($_POST['email']) && $_POST['email'] !== ''){
        $email = $_POST['email'];
    } else {
        echo json_encode(['message' => 'Không tìm thấy biến email', 'status' => 3]);
        exit;
    }
    if (isset($_POST['phone']) && $_POST['phone'] !== ''){
        $phone = $_POST['phone'];
    } else {
        echo json_encode(['message' => 'Không tìm thấy biến phone', 'status' => 4]);
        exit;
    }
    //Check if there's a Reader with this email
    //Prepare Statement Query
    $stmt = $db->prepare("SELECT * FROM Readers WHERE email = ?");
    if ($stmt === false) {
        // Handle statement preparation error
        http_response_code(500);
        echo json_encode(['message' => 'Chuẩn bị statement thất bại (lỗi kết nối database): ' . $db->error, 'status' => 5]);
        exit;
    }
    $stmt->bind_param('s', $email);
    //Execute Query
    $result = $stmt->execute();
    if ($result){
        $result = $stmt->get_result();
        if ($result->num_rows > 1){
            echo json_encode(['message' => 'Đã có độc giả với email này', 'status' => 6]);
            exit;
        } else {
            //Prepare Statement Query
            $stmt = $db->prepare("UPDATE Readers SET name = ?, email = ?, phone = ? WHERE id = ?");
            if ($stmt === false) {
                // Handle statement preparation error
                http_response_code(500);
                echo json_encode(['message' => 'Chuẩn bị statement thất bại (lỗi kết nối database): ' . $db->error, 'status' => 7]);
                exit;
            }
            $stmt->bind_param('sssi', $name, $email, $phone, $id);
            //Execute Query
            $result = $stmt->execute();
            //Check if Query executed successfully
            if ($result) {
                http_response_code(200);
                echo json_encode(['message' => 'Cập nhật độc giả thành công', 'status' => 8]);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Cập nhật độc giả không thành công, Error at: ' . $stmt->error, 'status' => 9]);
            }
        }
    }
    $db->close();
}
?>
