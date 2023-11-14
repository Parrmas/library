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
            <li class="breadcrumb-item active" style="font-size: 30px">Quản lý sách</li>
        </ol>
        <!--Add section-->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Thêm sách mới
            </div>
            <div class="card-body">
                <form id="formAdd" method="" action="" enctype="multipart/form-data">
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputName" type="text" placeholder="" name="name" required />
                        <label for="inputName">Tên sách</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputAuthor" type="text" placeholder="" name="author" required />
                        <label for="inputAuthor">Tên tác giả</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" id="inputCategory" placeholder="" name="category_id">
                            <option>--Chọn chủ đề--</option>
                            <?php foreach ($categories as $row): ?>
                                <option value="<?=$row->id;?>"><?=$row->name;?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="inputCategory">Chủ đề</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputISBN" type="text" placeholder="" name="isbn" required />
                        <label for="inputISBN">ISBN</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputAvailCopy" type="number" placeholder="" name="avail_copy" min="0" required  />
                        <label for="inputAvailCopy">Số lượng hiện có</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control-file" id="fileToUpload" type="file" name="fileToUpload" placeholder="Thêm ảnh" required />
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
                Sửa thông tin sách
            </div>
            <div class="card-body">
                <form id="formUpdate" method="" action="" enctype="multipart/form-data">
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputNameUpdate" type="text" placeholder="" name="name" required />
                        <label for="inputNameUpdate">Tên sách</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputAuthorUpdate" type="text" placeholder="" name="author" required />
                        <label for="inputAuthorUpdate">Tên tác giả</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" id="inputCategoryUpdate" placeholder="" name="category_id">
                            <option>--Chọn chủ đề--</option>
                            <?php foreach ($categories as $row): ?>
                                <option value="<?=$row->id;?>"><?=$row->name;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputISBNUpdate" type="text" placeholder="" name="isbn" required />
                        <label for="inputISBN">ISBN</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputAvailCopyUpdate" type="number" placeholder="" name="avail_copy" min="0" required />
                        <label for="inputAvailCopy">Số lượng hiện có</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control-file" id="fileToUploadUpdate" type="file" name="fileToUpload" placeholder="Thêm ảnh" />
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
                Danh sách sách
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input id="search-input" type="text" class="form-control" oninput="search_books()" placeholder="Search..."/>
                    </div>
                </div>
                <table class="table">
                    <tr>
                        <th style="text-align: center">Book Cover</th>
                        <th>Name</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>ISBN</th>
                        <th>Available Copy</th>
                        <th>Action</th>
                    </tr>
                    <tbody id="books-list">
                    <?php foreach ($books as $row): ?>
                        <tr>
                            <td style="text-align: center"><img src="api/images/<?= $row->img ?>" height="250px" width="250px" style="object-fit: contain"/></td>
                            <td style="font-size: 20px; vertical-align: middle"><?= $row->name ?></td>
                            <td style="font-size: 20px; vertical-align: middle"><?= $row->author ?></td>
                            <td style="font-size: 20px; vertical-align: middle"><?= $row->category_name ?></td>
                            <td style="font-size: 20px; vertical-align: middle"><?= $row->isbn ?></td>
                            <td style="font-size: 20px; vertical-align: middle; text-align: center"><?= $row->avail_copy ?></td>
                            <td style="vertical-align: middle; horiz-align: right">
                                <button class="btn btn-primary btn-edit"
                                        data-id="<?= $row->id; ?>"
                                        data-name="<?= $row->name; ?>"
                                        data-author="<?= $row->author; ?>"
                                        data-category="<?= $row->category_id; ?>"
                                        data-isbn="<?= $row->isbn; ?>"
                                        data-availcopy="<?= $row->avail_copy ?>"
                                        type="button">Sửa</button>
                                <button class="btn btn-danger btn-delete" data-id="<?=$row->id;?>">Xoá</button>
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
            $("#inputAuthorUpdate").val($(this).data('author'));
            $("#inputCategoryUpdate").val($(this).data('category'));
            $("#inputISBNUpdate").val($(this).data('isbn'));
            $("#inputAvailCopyUpdate").val($(this).data('availcopy'));

            $("#form-edit").css('display','block');
        });
    });
    function search_books() {
        var value = $('#search-input').val().toLowerCase();
        $('#books-list tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    };
    $(function(){
        $("#inputISBN").change(function(){
            event.preventDefault();
            var isbn = $("#inputISBN").val();
            var regex = /^97(8|9)[-\d]{10}\d$/;
            if (!regex.test(isbn)) {
                $("#inputISBN")[0].setCustomValidity('Invalid ISBN-13 code');
                $("#inputISBN")[0].reportValidity();
            } else {
                var books = <?php echo $booksJson; ?>;
                var isbnExists = books.some(function(book) {
                    return book.isbn === isbn;
                });
                if (isbnExists) {
                    $("#inputISBN")[0].setCustomValidity('ISBN already exists');
                    $("#inputISBN")[0].reportValidity();
                }
            }
        });
        $("#inputISBNUpdate").change(function(){
            event.preventDefault();
            var isbn = $("#inputISBNUpdate").val();
            var regex = /^97(8|9)[-\d]{10}\d$/;
            if (!regex.test(isbn)) {
                $("#inputISBNUpdate")[0].setCustomValidity('Invalid ISBN-13 code');
                $("#inputISBNUpdate")[0].reportValidity();
            } else {
                var books = <?php echo $booksJson; ?>;
                var isbnExists = books.some(function(book) {
                    return book.isbn === isbn;
                });
                if (isbnExists) {
                    $("#inputISBNUpdate")[0].setCustomValidity('ISBN already exists');
                    $("#inputISBNUpdate")[0].reportValidity();
                } else {
                    $("#buttonAdd").closest('form').submit();
                }
            }
        });
        $("#buttonAdd").click(function(event){
            event.preventDefault();
            var name = $('#inputName').val();
            var author = $('#inputAuthor').val();
            var category_id = $('#inputCategory').val();
            var isbn = $('#inputISBN').val();
            var avail_copy = $('#inputAvailCopy').val();
            var fileToUpload = $('#fileToUpload')[0].files[0];
            var formData = new FormData();
            formData.append('name', name);
            formData.append('author', author);
            formData.append('category_id', category_id);
            formData.append('isbn', isbn);
            formData.append('avail_copy', avail_copy);
            formData.append('fileToUpload', fileToUpload);
            formData.append('add',true);
            var confirmation = window.confirm("Bạn chắc chắn thêm sách mới?");
            if (confirmation)
            {
                $.ajax({
                url: 'https://vutt94.io.vn/library/api/api_books.php',
                type: 'POST',
                data: formData, dataType: 'json',
                processData: false,
                contentType: false,
                success: function(data) {
                    console.log(data);
                    // You can add more actions here based on the response from the server
                    if (data.status == "success") {
                        alert("Thêm sách mới thành công!");
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + error.message);
                    alert("Thêm sách thất bại!");
                },
            });
            }
        });
        $("#buttonUpdate").click(function(event){
            event.preventDefault();
            var id = $('#inputIdUpdate').val();
            var name = $('#inputNameUpdate').val();
            var author = $('#inputAuthorUpdate').val();
            var category_id = $('#inputCategoryUpdate').val();
            var isbn = $('#inputISBNUpdate').val();
            var avail_copy = $('#inputAvailCopyUpdate').val();
            var fileToUpload = $('#fileToUploadUpdate')[0].files[0];
            var formData = new FormData();
            formData.append('id',id);
            formData.append('name', name);
            formData.append('author', author);
            formData.append('category_id', category_id);
            formData.append('isbn', isbn);
            formData.append('avail_copy', avail_copy);
            formData.append('fileToUpload', fileToUpload);
            formData.append('edit',true);
            var confirmation = window.confirm("Bạn chắc chắn sửa thông tin sách này?");
            if (confirmation)
            {
                $.ajax({
                    url: 'https://vutt94.io.vn/library/api/api_books.php',
                    type: 'POST',
                    data: formData,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,  // tell jQuery not to set contentType
                    timeout: 5000,
                    success: function(data) {
                        // You can add more actions here based on the response from the server
                        if (data.status == "success") {
                            alert("Sửa thông tin sách thành công!");
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error: ' + error.message);
                        alert("Sửa thông tin sách thất bại!");
                    },
                });
            }
        });
        $(".btn-delete").click(function(event){
            var id = $(this).data('id');
            var confirmation = window.confirm("Bạn chắc chắn xóa sách này?");
            if (confirmation)
            {
                $.ajax({
                    url: 'https://vutt94.io.vn/library/api/api_books.php',
                    type: 'POST',
                    data: {
                        id: id,
                        delete: true
                    },
                    success: function(data) {
                        console.log(data);
                        // You can add more actions here based on the response from the server
                        if (data.status == "success") {
                            alert("Xóa sách thành công!");
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error: ' + error.message);
                        alert("Xóa sách thất bại!");
                    },
                });
            }
        })
    });
</script>
<?php include 'foot.php'?>
