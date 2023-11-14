<?php
    // Check if the admin is logged in
    session_start();
    if (!isset($_SESSION['admin'])) {
        header('Location: admin_login.php');
        exit();
    }
?>
<?php
//Get all categories
$url = 'http://vutt94.io.vn/library/api/api_categories.php';

$client = curl_init($url);
curl_setopt($client, CURLOPT_RETURNTRANSFER,true);
$response = curl_exec($client);

$categories = json_decode($response);

include 'api/connection.php';
$books_in_ea_cate = array();
$query = "SELECT category_id, COUNT(id) as amount FROM Books GROUP BY category_id";
$result = $db->query($query) or die("Error at: " . $db->error);
while ($row = mysqli_fetch_assoc($result)){
    $books_in_ea_cate[$row['category_id']] = $row['amount'];
}
?>
<?php include 'head.php'; ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Quản lý thư viện Pạt's Lib </h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active" style="font-size: 30px">Quản lý chủ đề</li>
        </ol>
        <!--Add section-->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Thêm chủ đề mới
            </div>
            <div class="card-body">
                <form>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputName" type="text" placeholder="" name="name" required />
                        <label for="inputName">Tên chủ đề</label>
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
                Sửa thông tin chủ đề
            </div>
            <div class="card-body">
                <form>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputNameUpdate" type="text" placeholder="" name="name" required />
                        <label for="inputNameUpdate">Tên sách</label>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <input id="inputIdUpdate" type="hidden" name="id"/>
                        <button id="buttonUpdate" type="submit" name="edit" value="1" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
        <!--List section--!>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Danh sách chủ đề
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input id="search-input" type="text" class="form-control" oninput="search_categories()" placeholder="Search..."/>
                    </div>
                </div>
                <table class="table" style="width: 100%">
                    <tr>
                        <th style="width: 50%; text-align: center">Name</th>
                        <th style="width: 50%; text-align: center">Number of Books</th>
                        <th>Action</th>
                    </tr>
                    <tbody id="categories-list">
                    <?php foreach ($categories as $row): ?>
                        <tr>
                            <td style="font-size: 20px; vertical-align: middle; text-align: center"><?= $row->name ?></td>
                            <td style="font-size: 20px; vertical-align: middle; text-align: center;"><?= isset($books_in_ea_cate[$row->id]) ? $books_in_ea_cate[$row->id] : 0 ?></td>
                            <td style="display: flex; justify-content: flex-end;">
                                <button class="btn btn-primary btn-edit"
                                        data-id="<?= $row->id; ?>"
                                        data-name="<?= $row->name; ?>"
                                        type="button">Sửa</button>
                                <button class="btn btn-danger btn-delete" data-id="<?= $row->id; ?>">Xoá</button>
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
            $("#inputNameUpdate").val($(this).data('name'));

            $("#form-edit").css('display','block');
        });
        $("#buttonAdd").click(function(){
           var name = $("#inputName").val();
            var confirmation = window.confirm("Bạn chắc chắn thêm chủ đề này?");
            if (confirmation)
            {
                $.ajax({
                    url: "https://vutt94.io.vn/library/api/api_categories.php",
                    type: "POST",
                    data: {
                        name: name,
                        add : true
                    },
                    success: function(data){
                        if (data.status == "success"){
                            alert("Thêm chủ đề thành công!");
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error: ' + error.message);
                        alert("Thêm chủ đề thất bại!");
                    },
                })
            }
        });
        $("#buttonUpdate").click(function(){
            var id = $("#inputIdUpdate").val();
            var name = $("#inputNameUpdate").val();
            var confirmation = window.confirm("Bạn chắc chắn sửa thông tin chủ đề này?");
            if (confirmation)
            {
                $.ajax({
                    url: "https://vutt94.io.vn/library/api/api_categories.php",
                    type: "POST",
                    data: {
                        id : id,
                        name: name,
                        edit: true
                    },
                    success: function(data){
                        if (data.status == "success"){
                            alert("Sửa thông tin chủ đề thành công!");
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error: ' + error.message);
                        alert("Sửa thông tin chủ đề thất bại!");
                    },
                })
            }
        });
        $(".btn-delete").click(function(){
            var id = $(this).data("id");
            var confirmation = window.confirm("Bạn chắc chắn xóa chủ đề này?");
            if (confirmation)
            {
                $.ajax({
                    url: "https://vutt94.io.vn/library/api/api_categories.php",
                    type: "POST",
                    data: {
                        id : id,
                        delete: true
                    },
                    success: function(data){
                        if (data.status == "success"){
                            alert("Xóa chủ đề thành công!");
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error: ' + error.message);
                        alert("Xóa chủ đề thất bại!");
                    },
                })
            }
        });
    });
    function search_categories() {
        var value = $('#search-input').val().toLowerCase();
        $('#categories-list tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    };
</script>
<?php include 'foot.php'?>
