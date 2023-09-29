<?php
// Check if the admin is logged in
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit();
}
?>
<?php
    $totalBooks = 0;
    $notReturnedBooks = 0;
    $totalReaders = 0;

    //Get total books
    $url = 'http://vutt94.io.vn/library/api/api_books.php';

    $client = curl_init($url);
    curl_setopt($client, CURLOPT_RETURNTRANSFER,true);
    $response = curl_exec($client);

    $books = json_decode($response);
    foreach ($books as $row){
        $totalBooks += 1;
    }
    //Get total book borrowed
    //TO-DO

    //Get total readers
    $url = 'http://vutt94.io.vn/library/api/api_readers.php';

    $client = curl_init($url);
    curl_setopt($client, CURLOPT_RETURNTRANSFER,true);
    $response = curl_exec($client);

    $readers = json_decode($response);
    foreach ($readers as $row){
        $totalReaders += 1;
    }

?>
?>
<?php include 'head.php'?>
<main>
    <style>
        /* Additional custom styles for card appearance */
        .card {
            border: 1px solid #e0e0e0; border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-size: 1.5rem; font-weight: bold;
        }
        .card-text {
            font-size: 1.2rem;
        }
    </style>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Quản lý thư viện Pạt's Lib </h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active" style="font-size: 30px">Thống kê</li>
        </ol>
        <div class="row">
            <div class="col-md-4">
                <div class="card" style="border: 1px solid #e0e0e0; border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); background-color: lightblue">
                    <div class="card-body">
                        <h3 class="card-title" style="font-size: 1.5rem; font-weight: bold;">Number of Books</h3>
                        <p class="card-text" style="font-size: 90px;"><?php echo $totalBooks; ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card" style="border: 1px solid #e0e0e0; border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);background-color: #ff3333">
                    <div class="card-body">
                        <h3 class="card-title" style="font-size: 1.5rem; font-weight: bold; ">Books Borrowed but Not Returned</h3>
                        <p class="card-text"  style="font-size: 90px;"><?php echo $notReturnedBooks; ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card" style="border: 1px solid #e0e0e0; border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);background-color: lightgreen">
                    <div class="card-body">
                        <h3 class="card-title" style="font-size: 1.5rem; font-weight: bold; ">Number of Readers</h3>
                        <p class="card-text"  style="font-size: 90px;"><?php echo $totalReaders; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include 'foot.php'?>