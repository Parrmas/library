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
<?php include 'head_employee.php'; ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Quản lý thư viện Pạt's Lib </h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active" style="font-size: 30px">Quản lý sách</li>
        </ol>
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
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<script>
    function search_books() {
        var value = $('#search-input').val().toLowerCase();
        $('#books-list tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    };
</script>