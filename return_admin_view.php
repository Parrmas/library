<?php
// Check if the admin is logged in
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit();
}
?>

<?php
//Get all borrows
$url = 'http://vutt94.io.vn/library/api/api_getBorrowUnreturned.php';

$client = curl_init($url);
curl_setopt($client, CURLOPT_RETURNTRANSFER,true);
$response = curl_exec($client);

$borrows = json_decode($response);

//Get all returns
$url = 'http://vutt94.io.vn/library/api/api_bookreturn.php';

$client = curl_init($url);
curl_setopt($client, CURLOPT_RETURNTRANSFER,true);
$response = curl_exec($client);

$returns = json_decode($response);
?>
<?php include 'head.php'; ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Quản lý thư viện Pạt's Lib </h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active" style="font-size: 30px">Quản lý sách</li>
        </ol>
        <!--Add section-->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Thêm phiếu trả sách
            </div>
            <div class="card-body">
                <form id="formAdd">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div>
                                <label for="inputBorrow" style="width: 100%">
                                    <b>Chọn phiếu mượn sách:</b>
                                    <select class="form-select" id="inputBorrow" name="borrow_id" style="width: 100%" placeholder="Chọn độc giả" required>
                                        <?php foreach ($borrows as $row): ?>
                                            <option value="<?= $row->id;?>">Mã phiếu mượn: <?=$row->id;?> - <?=$row->reader_name;?> - <?=$row->book_name;?></option>
                                        <?php endforeach;?>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6" style="display: flex; align-items: flex-end">
                            <p id="idFine" class="form-text" style="margin-bottom: 0px; font-size: 20px"></p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <button id="buttonAdd" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
        <!--Edit section-->
        <div id="form-edit" class="card mb-4" style="display: none">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Sửa phiếu trả sách
            </div>
            <div class="card-body">
                <form id="formEdit">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div>
                                <label for="inputBorrowUpdate" style="width: 100%">
                                    <b>Chọn phiếu mượn sách:</b>
                                    <select class="form-select" id="inputBorrowUpdate" name="borrow_id" style="width: 100%" placeholder="Chọn độc giả" required>
                                        <?php foreach ($borrows as $row): ?>
                                            <option value="<?= $row->id;?>">Mã phiếu mượn:<?=$row->id;?> - <?=$row->reader_name;?> - <?=$row->book_name;?></option>
                                        <?php endforeach;?>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6" style="display: flex; align-items: flex-end">
                            <p id="idFineUpdate" class="form-text" style="margin-bottom: 0px; font-size: 20px"></p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <input id="inputIdUpdate" type="hidden" name="id"/>
                        <button id="buttonUpdate" class="btn btn-success">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
        <!--List section--!>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Danh sách phiếu trả sách
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input id="search-input" type="text" class="form-control" oninput="search_return()" placeholder="Search..."/>
                    </div>
                </div>
                <table class="table">
                    <tr>
                        <th>Mã phiếu mượn</th>
                        <th>Ngày trả</th>
                        <th>Phí trễ</th>
                        <th>Hành động</th>
                    </tr>
                    <tbody id="return-list">
                    <?php foreach ($returns as $row): ?>
                        <tr style="vertical-align: middle">
                            <td><?= $row->borrow_id?></td>
                            <td><?= date_format(new DateTime($row->return_date),'d/m/Y')?></td>
                            <td><?= number_format($row->fine, 0, ',', ',')?> VNĐ</td>
                            <td>
                                <button class="btn btn-primary btn-edit"
                                        data-id="<?= $row->id; ?>"
                                        data-borrow="<?= $row->borrow_id; ?>">Sửa</button>
                                <button class="btn btn-danger btn-delete"
                                        data-id="<?= $row->id; ?>"
                                        data-borrow="<?= $row->borrow_id; ?>">Xoá</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
</main>
<script>
    $(function(){
        $(".btn-edit").click(function(){
            $("#inputIdUpdate").val($(this).data('id'));
            $("#inputBorrowUpdate").val($(this).data('borrow'));

            $("#form-edit").css('display','block');
        });
    });
    function search_return() {
        var value = $('#search-input').val().toLowerCase();
        $('#return-list tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    };
    $(function(){
        $("#inputBorrow").change(function(event){
            var id = $("#inputBorrow").val();
            $.ajax({
                url: "https://vutt94.io.vn/library/api/api_bookborrow.php?id="+id,
                type: "GET",
                success: function (data) {
                    if (data.length > 0){
                        var currentDate = new Date();
                        var dueDate = new Date(data[0].due_date);
                        var diffTime = Math.abs(currentDate - dueDate);
                        var diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

                        var fine = 0;
                        if(currentDate > dueDate){
                            fine = diffDays * 5000;
                        }
                        $("#idFine").text("Phí nộp trễ: " + fine.toLocaleString() + " VNĐ");
                    }
                }, error: function (error) {
                    console.log(error);
                }
            });
        });
        $("#inputBorrowUpdate").change(function(event){
            var id = $("#inputBorrowUpdate").val();
            $.ajax({
                url: "https://vutt94.io.vn/library/api/api_bookborrow.php?id="+id,
                type: "GET",
                success: function (data) {
                    if (data.length > 0){
                        var currentDate = new Date();
                        var dueDate = new Date(data[0].due_date);
                        var diffTime = Math.abs(currentDate - dueDate);
                        var diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

                        var fine = 0;
                        if(currentDate > dueDate){
                            fine = diffDays * 5000;
                        }
                        $("#idFineUpdate").text("Phí nộp trễ: " + fine.toLocaleString() + " VNĐ");
                    }
                }, error: function (error) {
                    console.log(error);
                }
            });
        });
    });
</script>
<script src="js/jquery-3.7.0.min.js"></script>
<script src="js/datepicker.js"></script>
<script>
    $(function() {
        $("#buttonAdd").click(function () {
            var borrow_id = $("#inputBorrow").val();
            $.ajax({
                url: "https://vutt94.io.vn/library/api/api_bookreturn.php",
                type: "POST",
                data: {
                    borrow_id: borrow_id,
                    add: true
                }, success: function (data) {
                    if (data.status == 'success'){
                        var confirmation = window.confirm('Lập phiếu trả sách thành công!');
                        if (confirmation){
                            location.reload();
                        }
                    }
                }, error: function (error) {
                    alert("Lập phiếu trả sách thất bại!");
                }
            })
        })
        $(".btn-delete").click(function () {
            var id = $(this).data('id');
            var borrow_id = $(this).data('borrow');
            $.ajax({
                url: "https://vutt94.io.vn/library/api/api_bookreturn.php",
                type: "POST",
                data: {
                    id: id,
                    borrow_id: borrow_id,
                    delete: true
                }, success: function (data) {
                    if (data.status == 'success'){
                        var confirmation = window.confirm('Xóa phiếu trả sách thành công!');
                        if (confirmation){
                            location.reload();
                        }
                    }
                }, error: function (error) {
                    alert("Xóa phiếu trả sách thất bại!");
                }
            })
        });
        $("#buttonUpdate").click(function(){
            var id = $("#inputIdUpdate").val();
            var borrow_id = $("#inputBorrowUpdate").val();
            $.ajax({
                url: "https://vutt94.io.vn/library/api/api_bookreturn.php",
                type: "POST",
                timeout: 5000,
                data: {
                    id: id,
                    borrow_id: borrow_id,
                    edit: true
                }, success: function (data) {
                    if (data.status == 'success'){
                        var confirmation = window.confirm('Cập nhật phiếu trả sách thành công!');
                        if (confirmation){
                            location.reload();
                        }
                    }
                }, error: function (error) {
                    alert("Cập nhật phiếu trả sách thất bại!");
                }
            });
        });
    });
</script><link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(window).on('load', function() {
        $("#inputBorrow").select2({
            placeholder: 'Chọn phiếu mượn sách',
        });
        $("#inputBorrowUpdate").select2({
            placeholder: 'Chọn sách',
        });
    });
</script>
<?php include 'foot.php'?>
