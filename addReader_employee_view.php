
?>
<?php include 'head_employee.php'; ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Quản lý thư viện Pạt's Lib </h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active" style="font-size: 30px">Quản lý độc giả</li>
        </ol>
        <!--Add section-->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Thêm độc giả mới
            </div>
            <div class="card-body">
                <form>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputName" type="text" placeholder="" name="name" required />
                        <label for="inputName">Tên độc giả</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputEmail" type="text" placeholder="" name="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" required />
                        <label for="inputEmail">Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputPhone" type="text" placeholder="" name="phone" pattern="[0-9]{10}" required />
                        <label for="inputPhone">Số điện thoại</label>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <button id="buttonAdd" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script src="js/jquery-3.7.0.min.js"></script>
<script>
    $(function () {
        $("#buttonAdd").click(function (event) {
            var name = $("#inputName").val();
            var email = $("#inputEmail").val();
            var phone = $("#inputPhone").val();
            var confirmation = window.confirm("Bạn chắc chắn thêm độc giả này?");
            if (confirmation) {
                $.ajax({
                    url: "https://vutt94.io.vn/library/api/api_addReader.php",
                    type: "POST",
                    data: {
                        name: name,
                        email: email,
                        phone: phone
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.status == 7) {
                            var confirmation = window.confirm(data.message);
                            if (confirmation) {
                                window.location.href = "listReader_employee_view.php";
                            }
                        }
                    },
                    error: function (data) {
                        alert(data.message);
                    }
                })
            }
        })
    })
</script>
<?php include 'foot.php'?>