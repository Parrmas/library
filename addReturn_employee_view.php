
<?php
//Get all borrows
$url = 'http://vutt94.io.vn/library/api/api_getBorrowUnreturned.php';

$client = curl_init($url);
curl_setopt($client, CURLOPT_RETURNTRANSFER,true);
$response = curl_exec($client);

$borrows = json_decode($response);
?>
<?php include 'head_employee.php'; ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Quản lý thư viện Pạt's Lib </h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active" style="font-size: 30px">Quản lý phiếu trả sách</li>
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
    </div>
</main>
<script>
    $(function(){
        $("#inputBorrow").blur(function(event){
            var id = $("#inputBorrow").val();
            $.ajax({
                url: "https://vutt94.io.vn/library/api/api_bookborrow.php?id="+id,
                type: "GET",
                success: function (data) {
                    if (data.length > 0){
                        var currentDate = new Date();
                        var dueDate = new Date(data[0].due_date);
                        var diffTime = Math.abs(currentDate - dueDate);
                        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

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
                            window.location.href ="listReturn_employee_view.php";
                        }
                    }
                }, error: function (error) {
                    alert("Lập phiếu trả sách thất bại!");
                }
            })
        });
    });
</script><link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(window).on('load', function() {
        $("#inputBorrow").select2({
            placeholder: 'Chọn phiếu mượn sách',
        });
    });
</script>
<?php include 'foot.php'?>

