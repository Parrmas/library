<?php
// Check if the admin is logged in
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit();
}
?>

<?php
//Get all books
$url = 'http://vutt94.io.vn/library/api/api_books.php';

$client = curl_init($url);
curl_setopt($client, CURLOPT_RETURNTRANSFER,true);
$response = curl_exec($client);

$books = json_decode($response);
$booksJson = json_encode($books);

//Get all readers
$url = 'http://vutt94.io.vn/library/api/api_readers.php';

$client = curl_init($url);
curl_setopt($client, CURLOPT_RETURNTRANSFER,true);
$response = curl_exec($client);

$readers = json_decode($response);
$readersJson = json_encode($readers);

//Get all borrows
$url = 'http://vutt94.io.vn/library/api/api_bookborrow.php';

$client = curl_init($url);
curl_setopt($client, CURLOPT_RETURNTRANSFER,true);
$response = curl_exec($client);

$borrows = json_decode($response);
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
                Thêm phiếu mượn sách
            </div>
            <div class="card-body">
                <form id="formAdd">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div>
                                <label for="inputReader" style="width: 100%">
                                    <b>Chọn độc giả:</b>
                                    <select class="form-select" id="inputReader" name="reader_id" style="width: 100%" placeholder="Chọn độc giả" required>
                                        <?php foreach ($readers as $row): ?>
                                            <option value="<?= $row->id;?>"><?=$row->name;?> - <?=$row->email;?></option>
                                        <?php endforeach;?>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <label for="inputBook" style="width: 100%">
                                <b>Chọn sách:</b>
                                <select class="form-select" id="inputBook" name="book_id" style="width: 100%" placeholder="Chọn sách" required>
                                    <?php foreach ($books as $row): ?>
                                        <option value="<?= $row->id;?>"><?=$row->name;?> - <?=$row->author;?></option>
                                    <?php endforeach;?>
                                </select>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input class="form-control" id="inputDueDate" type="text" name="due_date" placeholder="01/01/2023" required>
                                <label for="inputDueDate">Chọn hạn trả sách</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <input id="inputIdUpdate" type="hidden" name="id"/>
                        <button id="buttonAdd" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
        <!--Edit section-->
        <div id="form-edit" class="card mb-4" style="display: none">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Sửa phiếu mượn sách
            </div>
            <div class="card-body">
                <form id="formEdit">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div>
                                <label for="inputReaderUpdate" style="width: 100%">
                                    <b>Chọn độc giả:</b>
                                    <select class="form-select" id="inputReaderUpdate" name="reader_id" style="width: 100%" placeholder="Chọn độc giả" required>
                                        <?php foreach ($readers as $row): ?>
                                            <option value="<?= $row->id;?>"><?=$row->name;?> - <?=$row->email;?></option>
                                        <?php endforeach;?>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <label for="inputBookUpdate" style="width: 100%">
                                    <b>Chọn sách:</b>
                                    <select class="form-select" id="inputBookUpdate" name="book_id" style="width: 100%" placeholder="Chọn sách" required>
                                        <?php foreach ($books as $row): ?>
                                            <option value="<?= $row->id;?>"><?=$row->name;?> - <?=$row->author;?></option>
                                        <?php endforeach;?>
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input class="form-control" id="inputDueDateUpdate" type="text" name="due_date" placeholder="01/01/2023" required>
                                <label for="inputDueDateUpdate">Chọn hạn trả sách</label>
                            </div>
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
                Danh sách phiếu mượn sách
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input id="search-input" type="text" class="form-control" oninput="search_borrow()" placeholder="Search..."/>
                    </div>
                    <div class="col-md-6">
                        <select id="selectUnreturned" class="form-select">
                            <option value="all">Tất cả</option>
                            <option value="unreturned">Chưa trả</option>
                        </select>
                    </div>
                </div>
                <table class="table">
                    <tr>
                        <th>Mã phiếu</th>
                        <th>Độc giả mượn</th>
                        <th>Email độc giả mượn</th>
                        <th>Sách mượn</th>
                        <th>Ngày mượn</th>
                        <th>Hạn trả</th>
                        <th>Trạng thái trả</th>
                        <th>Hành động</th>
                    </tr>
                    <tbody id="borrow-list">
                    <?php foreach ($borrows as $row): ?>
                        <tr style="vertical-align: middle">
                            <td><?= $row->id?></td>
                            <td><?= $row->reader_name?></td>
                            <td><?= $row->reader_email?></td>
                            <td><?= $row->book_name?></td>
                            <td><?= date_format(new DateTime($row->borrow_date),'d/m/Y')?></td>
                            <td><?= date_format(new DateTime($row->due_date),'d/m/Y')?></td>
                            <td><?= $row->returned == 0 ? 'Chưa trả' : 'Đã trả'?></td>
                            <td>
                                    <button class="btn btn-primary btn-edit"
                                            data-id="<?= $row->id; ?>"
                                            data-reader="<?= $row->reader_id; ?>"
                                            data-book="<?= $row->book_id; ?>"
                                            data-due="<?= date_format(new DateTime($row->due_date),'d/m/Y'); ?>">Sửa</button>
                                    <button class="btn btn-danger btn-delete" data-id="<?= $row->id; ?>" data-book="<?= $row->book_id; ?>">Xoá</button>
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
            $("#inputReaderUpdate").val($(this).data('reader'));
            $("#inputBookUpdate").val($(this).data('book'));
            $("#inputDueDateUpdate").val($(this).data('due'));

            $("#form-edit").css('display','block');
        });
    });
    function search_borrow() {
        var value = $('#search-input').val().toLowerCase();
        $('#borrow-list tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    };
    function convertDateFormat(dateStr) {
        var dateParts = dateStr.split("/");
        return dateParts[1] + "/" + dateParts[0] + "/" + dateParts[2];
    }
    $(function(){
        $("#inputDueDate").change(function(event){
            var due_date_string = $("#inputDueDate").val();
            var converted_date_string = convertDateFormat(due_date_string);
            var due_date = new Date(converted_date_string);
            var curr_date = new Date();
            if (curr_date > due_date) {
                $("#inputDueDate")[0].setCustomValidity('Ngày trả sách không thể sớm hơn ngày hiện tại!');
                $("#inputDueDate")[0].reportValidity();
            } else {
                $("#inputDueDate")[0].setCustomValidity('');
            }
        });
    });
    $(function(){
        $("#inputDueDateUpdate").change(function(event){
            var due_date_string = $("#inputDueDateUpdate").val();
            var converted_date_string = convertDateFormat(due_date_string);
            var due_date = new Date(converted_date_string);
            var curr_date = new Date();
            if (curr_date > due_date) {
                $("#inputDueDateUpdate")[0].setCustomValidity('Ngày trả sách không thể sớm hơn ngày hiện tại!');
                $("#inputDueDateUpdate")[0].reportValidity();
            } else {
                $("#inputDueDateUpdate")[0].setCustomValidity('');
            }
        });
    });
</script>
<script src="js/jquery-3.7.0.min.js"></script>
<script src="js/datepicker.js"></script>
<script>
    $(function() {
        $("#inputDueDate").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd/mm/yyyy"
        });
        $("#inputDueDateUpdate").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd/mm/yyyy"
        });
        $("#buttonAdd").click(function () {
            var reader_id = $("#inputReader").val();
            var book_id = $("#inputBook").val();
            var due_date = $("#inputDueDate").val();
            var confirmation = window.confirm("Bạn chắc chắn lập phiếu mượn mới?");
            if (confirmation) {
                $.ajax({
                    url: "https://vutt94.io.vn/library/api/api_bookborrow.php",
                    type: "POST",
                    data: {
                        reader_id: reader_id,
                        book_id: book_id,
                        due_date: due_date,
                        add: true
                    }, success: function (data) {
                        if (data.status == 'success') {
                            var confirmation = window.confirm('Lập phiếu mượn sách thành công!');
                            if (confirmation) {
                                location.reload();
                            }
                        } else if (data.status == 'overload') {
                            var confirmation = window.confirm('Độc giả không thể mượn quá 3 cuốn sách!');
                            if (confirmation) {
                                location.reload();
                            }
                        }
                    }, error: function (error) {
                        alert("Lập phiếu mượn sách thất bại!");
                    }
                })
            }
        })
        $(".btn-delete").click(function () {
            var id = $(this).data('id');
            var book_id = $(this).data('book');
            var confirmation = window.confirm("Bạn chắc chắn xóa phiếu mượn này?");
            if (confirmation) {
                $.ajax({
                    url: "https://vutt94.io.vn/library/api/api_bookborrow.php",
                    type: "POST",
                    data: {
                        id: id,
                        book_id: book_id,
                        delete: true
                    }, success: function (data) {
                        if (data.status == 'success') {
                            var confirmation = window.confirm('Xóa phiếu mượn sách thành công!');
                            if (confirmation) {
                                location.reload();
                            }
                        }
                    }, error: function (error) {
                        alert("Xóa phiếu mượn sách thất bại!");
                    }
                })
            }
        });
        $("#buttonUpdate").click(function(){
            var id = $("#inputIdUpdate").val();
            var reader_id = $("#inputReaderUpdate").val();
            var book_id = $("#inputBookUpdate").val();
            var due_date = $("#inputDueDateUpdate").val();
            var confirmation = window.confirm("Bạn chắc chắn sửa thông tin phiếu mượn này");
            if (confirmation) {
                $.ajax({
                    url: "https://vutt94.io.vn/library/api/api_bookborrow.php",
                    type: "POST",
                    data: {
                        id: id,
                        reader_id: reader_id,
                        book_id: book_id,
                        due_date: due_date,
                        edit: true
                    }, success: function (data) {
                        if (data.status == 'success') {
                            var confirmation = window.confirm('Cập nhật phiếu mượn sách thành công!');
                            if (confirmation) {
                                location.reload();
                            }
                        }
                    }, error: function (error) {
                        alert("Cập nhật phiếu mượn sách thất bại!");
                    }
                });
            }
        });
        $("#selectUnreturned").change(function(){
            var select = $("#selectUnreturned").val();
            var value = "";
            if (select == "all"){
                value = "";
            } else if (select == "unreturned"){
                value = "Chưa trả".toLowerCase();
            }
            console.log(value);
            $('#borrow-list tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script><link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(window).on('load', function() {
        $("#inputReader").select2({
            placeholder: 'Chọn độc giả',
        });
        $("#inputBook").select2({
            placeholder: 'Chọn sách',
        });
        $("#inputReaderUpdate").select2({
            placeholder: 'Chọn độc giả',
        });
        $("#inputBookUpdate").select2({
            placeholder: 'Chọn sách',
        });
    });
</script>
<?php include 'foot.php'?>
