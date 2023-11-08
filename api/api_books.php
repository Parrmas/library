<?php
    header("Content-Type:application/json;");
    header("Access-Control-Allow-Origin: *");

    //GET method processing
    if ($_SERVER['REQUEST_METHOD'] === 'GET'){
        //Connect to db
        include 'connection.php';
        //Check db connection
        if ($db->connect_error){
            die("Connection failed: " . $db->connect_error);
        }
        //Build query
        $query =   "SELECT b.*,  c.name as category_name
                    FROM Books b
                    LEFT JOIN BookCategories c ON b.category_id = c.id";
        //Check if there's a request for specific book(id)
        if (isset($_GET['id'])){
            $id = $_GET['id'];
            $query .= " WHERE b.id = '$id'";
        } elseif (isset($_GET['category_id'])){ //Check if there's a request for specific category
            $category_id = $_GET['category_id'];
            $query .= "WHERE b.id = '$category_id'";
        }
        //Re-Order Query result
        $query .= " ORDER BY b.name ASC";
        //Exec query
        $result = $db->query($query) or die("Error at: " . $db->error);
        //Store data into array
        $books = array();
        while ($row = $result->fetch_assoc()){
            $books[] = $row;
        }
        //Close connection
        $db->close();
        //Return response
        echo json_encode($books, JSON_UNESCAPED_UNICODE);
    }

    //POST method processing
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        include 'connection.php';

        //Check connection
        if ($db->connect_error) {
            die ("Connection failed: " . $db->connect_error);
        }
        //If action is add
        if (isset($_POST['add'])) {

            //Load data for insert
            $name = $_POST['name'];
            $author = $_POST['author'];
            $category_id = $_POST['category_id'];
            $isbn = $_POST['isbn'];
            $avail_copy = $_POST['avail_copy'];

            //Prep image upload
            $target_dir = 'images/';
            $original_file_name = $_FILES['fileToUpload']['name']; // Get the original file name
            $timestamp = time(); // Get current timestamp
            $random_string = uniqid(); // Generate a unique random string

            // Generate a unique file name by combining the timestamp and random string
            $new_file_name = $timestamp . '_' . $random_string . '_' . $original_file_name;
            $target_file = $target_dir . basename($new_file_name);

            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            //Check image if fake
            $check = getimagesize($_FILES['fileToUpload']['tmp_name']);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $response = ['message' => 'File is not an image.'];
                $uploadOk = 0;
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                $uploadOk = 0;
                $response = ['message' => 'Sorry, file already exists.'];
            }

            // Allow certain file formats
            if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg' && $imageFileType != 'gif') {
                $uploadOk = 0;
                $response = ['message' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.'];
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $response = ['message' => 'Sorry, your file was not uploaded.'];
            } else {
                if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
                    $response = ['message' => 'The file ' . htmlspecialchars($original_file_name) . ' has been uploaded as ' . htmlspecialchars($new_file_name) . '.'];
                    $img = $new_file_name; // Use the unique file name
                } else {
                    $response = ['message' => 'Sorry, there was an error uploading your file.'];
                }
            }

            $stmt = $db->prepare("INSERT INTO Books (name, author, category_id, isbn, avail_copy, img) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssisss", $name, $author, $category_id, $isbn, $avail_copy, $img);

            $result = $stmt->execute();

            $db->close();

            if ($result) {
                header('Location:../books_admin_view.php');
            } else {
                die("Error: " . $stmt->error);
            }
        }
        if (isset($_POST['edit'])) {
            // Edit existing record
            $id = $_POST['id']; // Assuming you pass the ID of the record to be edited

            // Load updated data
            $name = $_POST['name'];
            $author = $_POST['author'];
            $category_id = $_POST['category_id'];
            $isbn = $_POST['isbn'];
            $avail_copy = $_POST['avail_copy'];

            // Check if a new image file is provided
            if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK) {
                //Prep the image
                $target_dir = 'images/';
                $original_file_name = $_FILES['fileToUpload']['name']; // Get the original file name
                $timestamp = time(); // Get current timestamp
                $random_string = uniqid(); // Generate a unique random string

                // Generate a unique file name by combining the timestamp and random string
                $new_file_name = $timestamp . '_' . $random_string . '_' . $original_file_name;
                $target_file = $target_dir . basename($new_file_name);

                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Check if the file is an image
                $check = getimagesize($_FILES['fileToUpload']['tmp_name']);
                if ($check !== false) {
                    // Allow certain image file formats (adjust as needed)
                    if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg' && $imageFileType != 'gif') {
                        $response = ['message' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.'];
                    }

                    //Delete the old image
                    $query = "SELECT img FROM Books WHERE id = '$id'";
                    $result = $db->query($query) or die("Error at: " . $db->error);
                    $old_file_path = "api/images/". $result;
                    if (file_exists($old_file_path)) {
                        unlink($old_file_path);
                    }

                    // Upload the new image
                    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
                        //Update file url in db
                        $img = $new_file_name; // Use the unique file name
                    } else {
                        $response = ['message' => 'Sorry, there was an error uploading your file.'];
                    }
                } else {
                    $response = ['message' => 'File is not an image.'];
                }
            }

            // Assuming $db is your MySQLi connection
            $stmt = $db->prepare("UPDATE Books SET name = ?, author = ?, category_id = ?, isbn = ?, avail_copy = ? WHERE id = ?");
            $stmt->bind_param("ssisii", $name, $author, $category_id, $isbn, $avail_copy, $id);

            if (isset($img)) {
                $stmt->prepare("UPDATE Books SET img = ? WHERE id = ?");
                $stmt->bind_param("si", $img, $id);
            }

            $result = $stmt->execute();

            if ($result) {
                echo json_encode(['message' => 'Record updated successfully']);
            } else {
                echo json_encode(['message' => 'Failed to update record']);
            }

            $db->close();

            header('Location:../books_admin_view.php');
        }
        if(isset($_POST['delete'])){
            // Edit existing record
            $id = $_POST['id'];

            $query = "DELETE FROM Books WHERE id = '$id'";
            $result = $db->query($query) or die("Error at: " . $db->error);
            $db->close();
            header('Location: ../books_admin_view.php');
        }
    }
?>
