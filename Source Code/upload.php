<?php include 'configh/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tải Lên</title>
    <link rel=icon href="assets/img/fav.ico" sizes="16x16" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="assets/js/swal.js"></script>
    <script src="assets/js/jquery.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.1/css/boxicons.min.css">
    <link rel="stylesheet" href="assets/css/mainpage.css">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
</head>
<body>
<?php include 'nav.php'; ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Upload File
                    </div>
                    <div class="card-body">
                        <form id="uploadForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="fileInput" class="form-label">Chọn file âm thanh(mp3)</label>
                                <input type="file" class="form-control" id="fileInput" name="file">
                            </div>
                            <div class="mb-3">
                                <label for="imageInput" class="form-label">Chọn File Ảnh(jpg,jpeg,png)</label>
                                <input type="file" class="form-control" id="imageInput" name="image">
                            </div>
                            <div class="mb-3">
                                <label for="titleInput" class="form-label">Song Title</label>
                                <input type="text" class="form-control" id="titleInput" name="title" placeholder="Enter song title">
                            </div>
                            <div class="mb-3">
                                <label for="artistInput" class="form-label">Artist Name</label>
                                <input type="text" class="form-control" id="artistInput" name="artist" placeholder="Enter artist name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Thể loại nhạc</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="genre" id="edm" value="edm">
                                    <label class="form-check-label" for="edm">
                                        EDM
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="genre" id="english" value="english">
                                    <label class="form-check-label" for="english">
                                        Nhạc tiếng Anh
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="genre" id="heroic" value="heroic">
                                    <label class="form-check-label" for="heroic">
                                        Nhạc hào hùng
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="genre" id="anime" value="anime">
                                    <label class="form-check-label" for="anime">
                                        Nhạc Anime
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="genre" id="other" value="other">
                                    <label class="form-check-label" for="other">
                                        Khác
                                    </label>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="uploadFile('<?php echo $_SESSION['fullname']; ?>')">Tải Lên</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var baseurl = window.location.protocol + "//" + window.location.host;
        function uploadFile(fullname) {
            var fileInput = $('#fileInput').get(0);
            var titleInput = $('#titleInput').val();
            var artistInput = $('#artistInput').val();
            var genreInput = $('input[name="genre"]:checked').val(); 
            var imageInput = $('#imageInput').get(0);

            var file = fileInput.files[0];
            var image = imageInput.files[0];

            if (!fileInput.files.length) {
                Swal.fire(
                    'Thông báo',
                    'Bạn chưa chọn tệp để tải lên',
                    'info'
                )
                return;
            }
            if(!titleInput || !artistInput) {
                Swal.fire(
                    'Thông báo',
                    'Vui lòng nhập tiêu đề và tên nghệ sĩ',
                    'info'
                )
                return;
            }
            if (!imageInput.files.length) {
                Swal.fire(
                    'Thông báo',
                    'Bạn chưa chọn hình ảnh để tải lên',
                    'info'
                )
                return;
            }

            var file = fileInput.files[0];
            var fileType = file.name.split('.').pop().toLowerCase();
            if (fileType != "mp3" && fileType != "wav") {
                Swal.fire(
                    'Thông báo',
                    'Vui lòng chọn file hợp lệ',
                    'error'
                )
                return;
            }
            var image = imageInput.files[0];
            var imageType = image.name.split('.').pop().toLowerCase();
            if (imageType != "jpg" && imageType != "png" && imageType != "jpeg") {
                Swal.fire(
                    'Thông báo',
                    'Vui lòng chọn file hợp lệ',
                    'error'
                )
                return;
            }
            if (!genreInput) {
                Swal.fire(
                    'Thông báo',
                    'Vui lòng chọn thể loại',
                    'info'
                )
                return;
            }
            Swal.fire({
                title: 'Xác nhận',
                icon: 'warning',
                text: `Xác nhận tải lên ?`,
                showCancelButton: true,
                confirmButtonText: "Xác nhận",

            }).then(function(result) {
                if (result.value) {
                    var formData = new FormData();
                    formData.append('type', 'upfile');
                    formData.append('musicFile', file);
                    formData.append('title', titleInput);
                    formData.append('artist', artistInput);
                    formData.append('genre', genreInput);
                    formData.append('imageFile', image);
                    formData.append('fullname', fullname);
                    $.ajax({
                        url: baseurl + '/module/ajax-song.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: formData,
                        processData: false,  
                        contentType: false,  
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Thông báo',
                                text: 'Please wait...',
                                icon: 'info',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            })
                        },
                        success: (data) => {
                            if (data.error) {
                                Swal.fire("Thông báo", data.msg, "error");
                            } else {
                                Swal.fire("Thông báo", data.msg, "success");
                                setTimeout(function() {
                                    location.href='index.php';
                                }, 500);
                            }
                        }
                    });
                }
            });
        }
    </script>
    <script src="assets/js/bootstrap.js"></script>
<body>