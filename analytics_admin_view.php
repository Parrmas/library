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

    include 'api/connection.php';
    $query = "SELECT bb.borrow_date, COUNT(bb.id) as num_borrow, COALESCE(SUM(br.fine),0) as total_fine
                        FROM BookBorrow bb 
                        LEFT JOIN BookReturn br ON bb.id = br.borrow_id
                        GROUP BY bb.borrow_date
                        ORDER BY bb.borrow_date DESC";
    $result = $result = $db->query($query) or die("Error at: " . $db->error);
    $data = array();
    while ($row = $result->fetch_assoc()){
        $data[] = $row;
    }
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
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card" style="height: 220px; border: 1px solid #e0e0e0; border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); background-color: lightblue">
                    <div class="card-body">
                        <div style="float: left; width: 50%; padding-left: 30px">
                            <p class="card-text" style="font-size: 70px;"><?php echo $totalBooks; ?></p>
                            <p class="card-text" style="font-size: 30px;">Books</p>
                        </div>
                        <div style=" display: flex; width: 50%; height: 100%; align-items: center; justify-content: flex-end">
                            <i class="fas fa-book" style="max-width: 50%; height: auto; opacity: 0.6"></i> <!-- Replace 'book_icon.png' with the path to your book icon image -->
                        </div>
                        <div style="clear: both;"></div> <!-- This div is used to clear the float, preventing it from affecting elements outside of the card -->
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card" style="height: 220px; border: 1px solid #e0e0e0; border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); background-color: orangered">
                    <div class="card-body">
                        <div style="float: left; width: 50%; padding-left: 30px">
                            <p class="card-text" style="font-size: 70px;"><?php echo $notReturnedBooks; ?></p>
                            <p class="card-text" style="font-size: 30px;">Not returned</p>
                        </div>
                        <div style=" display:flex; height: 100%; width: 50%; align-items: center; justify-content: flex-end">
                            <i class="fa-solid fa-xmark" style="max-width: 50%; height: auto; opacity: 0.6"></i> <!-- Replace 'book_icon.png' with the path to your book icon image -->
                        </div>
                        <div style="clear: both;"></div> <!-- This div is used to clear the float, preventing it from affecting elements outside of the card -->
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card" style="height: 220px; border: 1px solid #e0e0e0; border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); background-color: limegreen">
                    <div class="card-body">
                        <div style="float: left; width: 50%; padding-left: 30px">
                            <p class="card-text" style="font-size: 70px;"><?php echo $totalReaders; ?></p>
                            <p class="card-text" style="font-size: 30px;">Readers</p>
                        </div>
                        <div style=" display:flex; height: 100%; width: 50%; align-items: center; justify-content: flex-end">
                            <i class="fa-solid fa-users" style="max-width: 50%; height: auto; opacity: 0.6"></i> <!-- Replace 'book_icon.png' with the path to your book icon image -->
                        </div>
                        <div style="clear: both;"></div> <!-- This div is used to clear the float, preventing it from affecting elements outside of the card -->
                    </div>
                </div>
            </div>
        </div>
        <!--List section--!>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Thống kê
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select id = "selectQuery" class="form-select">
                            <option value="date">Theo ngày</option>
                            <option value="month">Theo tháng</option>
                            </select>
                            <label for = "selectQuery">Chọn loại thống kê</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select id = "monthQuery" class="form-select">
                                <option value="">Mặc định</option>
                                <option value="1">Tháng 1</option>
                                <option value="2">Tháng 2</option>
                                <option value="3">Tháng 3</option>
                                <option value="4">Tháng 4</option>
                                <option value="5">Tháng 5</option>
                                <option value="6">Tháng 6</option>
                                <option value="7">Tháng 7</option>
                                <option value="8">Tháng 8</option>
                                <option value="9">Tháng 9</option>
                                <option value="10">Tháng 10</option>
                                <option value="11">Tháng 11</option>
                                <option value="12">Tháng 12</option>
                            </select>
                            <label for = "monthQuery">Chọn tháng thống kê</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select id = "amountOrder" class="form-select">
                                <option value="">Mặc định</option>
                                <option value="asc">Tăng dần</option>
                                <option value="desc">Giảm dần</option>
                            </select>
                            <label for = "amountOrder">Sắp xếp số lượng sách</label>
                        </div>
                    </div>
                </div>
                <table class="table">
                    <tr>
                        <th>Ngày/Tháng</th>
                        <th>Số đơn</th>
                        <th>Tổng thu</th>
                    </tr>
                    <tbody id="analytic-list">
                    <?php foreach($data as $row): ?>
                        <tr>
                            <td><?=date_format(new DateTime($row['borrow_date']),'d/m/Y');?></td>
                            <td><?=$row['num_borrow'];?></td>
                            <td><?=number_format($row['total_fine'], 0, ',', ',');?> VNĐ</td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<script>
    $("#selectQuery").change(function () {
        queryData();
    });
    $("#amountOrder").change(function () {
        queryData();
    });
    $("#monthQuery").change(function () {
        queryData();
    });
    function queryData(){
        var selectQuery = $("#selectQuery").val();
        var amountOrder = $("#amountOrder").val();
        var monthQuery = $("#monthQuery").val();
        $.ajax({
            url: "https://vutt94.io.vn/library/api/api_analytics.php",
            type: "POST",
            data:{
                selectQuery: selectQuery,
                monthQuery: monthQuery,
                amountOrder: amountOrder,
            }, success: function(data){
                if (data.length > 0){
                    $("#analytic-list").empty();
                    $.each(data,function (key,value) {
                        if (value.borrow_date != null){
                            var row = $("<tr></tr>");
                            row.append("<td>" + convertDateFormat(value.borrow_date) + "</td>");
                            row.append("<td>" + value.num_borrow + "</td>");
                            row.append("<td>" + Number(value.total_fine).toLocaleString('en-US') + " VNĐ</td>");
                        } else if (value.month != null){
                            var row = $("<tr></tr>");
                            row.append("<td>Tháng " + value.month.toString() + "/" + value.year.toString() + "</td>");
                            row.append("<td>" + value.num_borrow + "</td>");
                            row.append("<td>" + Number(value.total_fine).toLocaleString('en-US') + " VNĐ</td>");
                        }
                        $("#analytic-list").append(row);
                    });
                } else {
                    $("#analytic-list").empty();
                    $("#analytic-list").append("<tr><td colspan='3' style='text-align: center;font-weight: bold'>Không có dữ liệu đang tìm.</td></tr>");
                }
            }, error: function(error) {
                console.log(error.message);
            }
        });
    }
    function convertDateFormat(borrow_date){
        let date = new Date(borrow_date);
        let day = date.getDate();
        let month = date.getMonth() + 1; // January is 0!
        let year = date.getFullYear();

        day = day.toString().padStart(2, '0');
        month = month.toString().padStart(2, '0');

        return day + "/" + month + "/" + year;
    }
</script>
<?php include 'foot.php'?>