<?php
//Get all employees
$url = 'http://vutt94.io.vn/library/api/api_readers.php';

$client = curl_init($url);
curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($client);

$readers = json_decode($response);
?>

<?php include 'head_employee.php'; ?>
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Quản lý thư viện Pạt's Lib </h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active" style="font-size: 30px">Quản lý độc giả</li>
            </ol>
            <!--Edit section-->
            <div id="form-edit" class="card mb-4" style="display: none">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Sửa thông tin độc giả
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="inputNameUpdate" type="text" placeholder="" name="name" required />
                            <label for="inputNameUpdate">Tên độc giả</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="inputEmailUpdate" type="text" placeholder="" name="email" required />
                            <label for="inputEmailUpdate">Email</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="inputPhoneUpdate" type="text" placeholder="" name="phone" required />
                            <label for="inputPhoneUpdate">Số điện thoại</label>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                            <input id="inputIdUpdate" type="hidden" name="id"/>
                            <button id="buttonUpdate" class="btn btn-primary">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
            <!--List section--!>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Danh sách đọc giả
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input id="search-input" type="text" class="form-control" oninput="search_readers()" placeholder="Search..."/>
                        </div>
                    </div>
                    <table class="table">
                        <tr>
                            <th>Tên độc giả</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th></th>
                        </tr>
                        <tbody id="readers-list">
                        <?php foreach ($readers as $row): ?>
                            <tr>
                                <td style="font-size: 20px; vertical-align: middle"><?= $row->name ?></td>
                                <td style="font-size: 20px; vertical-align: middle"><?= $row->email ?></td>
                                <td style="font-size: 20px; vertical-align: middle"><?= $row->phone ?></td>
                                <td>
                                    <form>
                                        <button class="btn btn-primary btn-edit"
                                                data-id="<?= $row->id; ?>"
                                                data-name="<?= $row->name; ?>"
                                                data-email="<?= $row->email; ?>"
                                                data-phone="<?= $row->phone; ?>"
                                                type="button">Sửa</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <script src="js/jquery-3.7.0.min.js"></script>
    <script>
        $(function(){
            $(".btn-edit").click(function(){
                $("#inputIdUpdate").val($(this).data('id'));
                $("#inputNameUpdate").val($(this).data('name'));
                $("#inputEmailUpdate").val($(this).data('email'));
                $("#inputPhoneUpdate").val($(this).data('phone'));

                $("#form-edit").css('display','block');
            });
            $("#buttonUpdate").click(function(event){
                event.preventDefault();
                var id = $("#inputIdUpdate").val();
                var name = $("#inputNameUpdate").val();
                var email = $("#inputEmailUpdate").val();
                var phone = $("#inputPhoneUpdate").val();
                $.ajax({
                    url: "https://vutt94.io.vn/library/api/api_editReader.php",
                    type: "POST",
                    data:{
                        id: id,
                        name: name,
                        email: email,
                        phone: phone
                    },
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        if (data.status == 8){
                            var confirmation = window.confirm(data.message);
                            if (confirmation){
                                location.reload();
                            }
                        }
                    },
                    error: function (data) {
                        alert(data.message);
                    }
                })
            });
        });
        function search_readers() {
            var value = $('#search-input').val().toLowerCase();
            $('#readers-list tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        };
    </script>
<?php include 'foot.php'?>