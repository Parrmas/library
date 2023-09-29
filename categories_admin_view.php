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
                <form method="POST" action="api/api_categories.php">
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputName" type="text" placeholder="" name="name" required />
                        <label for="inputName">Tên chủ đề</label>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <button id="buttonAdd" type="submit" name="add" value="1" class="btn btn-primary">Thêm</button>
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
                <form method="POST" action="api/api_categories.php">
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
                <table class="table">
                    <tr>
                        <th>ID</th>
                        <th>name</th>
                        <th></th>
                    </tr>
                    <tbody id="categories-list">
                    <?php foreach ($categories as $row): ?>
                        <tr>
                            <td style="font-size: 20px; vertical-align: middle"><?= $row->id ?></td>
                            <td style="font-size: 20px; vertical-align: middle"><?= $row->name ?></td>
                            <td>
                                <form method="POST" action="api/api_categories.php">
                                    <button class="btn btn-primary btn-edit"
                                            data-id="<?= $row->id; ?>"
                                            data-name="<?= $row->name; ?>"
                                            type="button">Sửa</button>
                                    <input type="hidden" name="id" value="<?= $row->id; ?>"/>
                                    <button class="btn btn-danger" type="submit" name="delete" value="1">Xoá</button>
                                </form>
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
    });
    function search_categories() {
        var value = $('#search-input').val().toLowerCase();
        $('#categories-list tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    };
</script>
<?php include 'foot.php'?>
