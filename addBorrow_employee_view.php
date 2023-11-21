

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

?>
<?php include 'head_employee.php'; ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Quản lý thư viện Pạt's Lib </h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active" style="font-size: 30px">Quản lý phiếu mượn sách</li>
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
    </div>
</main>
<script>
    function convertDateFormat(dateStr) {
        var dateParts = dateStr.split("/");
        return dateParts[1] + "/" + dateParts[0] + "/" + dateParts[2];
    }
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
        $("#buttonAdd").click(function (event) {
            event.preventDefault();
            var reader_id = $("#inputReader").val();
            var book_id = $("#inputBook").val();
            var due_date_string = $("#inputDueDate").val();
            var converted_date_string = convertDateFormat(due_date_string);
            var due_date = new Date(converted_date_string);
            var curr_date = new Date();
            if (curr_date > due_date) {
                $("#inputDueDate")[0].setCustomValidity('Ngày trả sách không thể sớm hơn ngày hiện tại!');
                $("#inputDueDate")[0].reportValidity();
                return;
            } else {
                var confirmation = window.confirm("Bạn chắc chắn lập phiếu mượn mới?");
                if (confirmation) {
                    $.ajax({
                        url: "https://vutt94.io.vn/library/api/api_bookborrow.php",
                        type: "POST",
                        data: {
                            reader_id: reader_id,
                            book_id: book_id,
                            due_date: due_date_string,
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
            }
        })
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
    });
</script>
<?php include 'foot.php'?>
