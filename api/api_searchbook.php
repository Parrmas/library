<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Get all phone data from the server
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['encodedQuery']) && $_POST['encodedQuery'] != "") {
        $search_query = '%' . $_POST['encodedQuery'] . '%';

        // Connect to the database (you should include your connection logic here)
        include 'connection.php';

        // Check database connection
        if ($db->connect_error) {
            http_response_code(500);
            echo json_encode(['message' => 'Kết nối database thất bại, Error at: ' . $db->error, 'status' => 0]);
        }

        // Prepare the statement query
        $stmt = $db->prepare("SELECT * FROM Books WHERE name LIKE ? ORDER BY name ASC");
        if ($stmt === false) {
            // Handle statement preparation error
            http_response_code(500);
            echo json_encode(['message' => 'Chuẩn bị statement thất bại (lỗi kết nối database): ' . $db->error, 'status' => 1]);
            exit;
        }

        $stmt->bind_param("s", $search_query);
        $result = $stmt->execute();

        if ($result) {
            $result = $stmt->get_result();
            $books = array(); // Create an array to store the matching phones

            while ($row = $result->fetch_assoc()) {
                $books[] = $row;
            }

            if (!empty($books)) {
                http_response_code(200);
                echo json_encode($books, JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(200);
                echo json_encode(['message' => 'Không sách đang tìm', 'status' => 2]);
            }
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Tìm điện thoại thất bại, Error at: ' . $stmt->error, 'status' => 3]);
        }
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Không có encodedQuery nhập vào', 'status' => 4]);
    }

    // Close the database connection
    $db->close();
}
?><?php
