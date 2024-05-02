<?php include 'configh/session.php'; ?>
<?php
    $stmt = $conn->prepare("SELECT * FROM tai_khoan WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $currentAvatarPath = $user['pathavt'];
?>
<head>
    <title>Tài Khoản</title>
    <link rel=icon href="assets/img/fav.ico" sizes="16x16" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="assets/js/swal.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <link rel="stylesheet" href="assets/css/mainpage.css">
</head>
<body>
<?php include 'nav.php'; ?>
<div id="layout-wrapper">
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mt-4 mt-lg-0">
                                            <form>
                                                <div class="row mb-4">
                                                    <label for="email" class="col-sm-3 col-form-label">Email</label>
                                                    <div class="col-sm-9">
                                                        <input style=" border:1px solid #000; background-color: #fff;"  type="text" value="<?php echo $_SESSION['email'] ?>" class="form-control" id="email" readonly>
                                                    </div>
                                                </div>
                                                <div class="row mb-4">
                                                    <label for="oldpassword" class="col-sm-3 col-form-label">Tên đăng nhập</label>
                                                    <div class="col-sm-9">
                                                        <input style=" border:1px solid #000; background-color: #fff;" type="text" class="form-control" id="username" value="<?php echo $_SESSION['username'] ?>"readonly>
                                                    </div>
                                                </div>
                                                <div class="row mb-4">
                                                    <label for="oldpassword" class="col-sm-3 col-form-label">Full Name</label>
                                                    <div class="col-sm-9">
                                                        <input  type="text" class="form-control" id="fullname" value="<?php echo $_SESSION['fullname'] ?>">
                                                    </div>
                                                </div>
                                                <div class="row mb-4">
                                                    <label for="oldpassword" class="col-sm-3 col-form-label">Mật Khẩu Hiện Tại</label>
                                                    <div class="col-sm-9">
                                                        <input  type="password" class="form-control" id="oldpassword">
                                                    </div>
                                                </div>
                                                <div class="row mb-4">
                                                    <label for="password" class="col-sm-3 col-form-label">Mật Khẩu Mới</label>
                                                    <div class="col-sm-9">
                                                        <input  type="password" class="form-control" id="newpassword">
                                                    </div>
                                                </div>
                                                <div class="row mb-4">
                                                    <label for="repassword" class="col-sm-3 col-form-label">Xác Nhận MK</label>
                                                    <div class="col-sm-9">
                                                        <input  type="password" class="form-control" id="repassword">
                                                    </div>
                                                </div>
                                                <div class="row justify-content-end">
                                                    <div class="col-sm-9">
                                                        <button id="submit" type="button" onclick="changepw()" class="btn btn-primary w-md">Submit</button> 
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 text-center">
                                        <form enctype="multipart/form-data">
                                            <div class="row mb-4">
                                                <label for="avatar" class="col-sm-3 col-form-label">Avatar</label>
                                                <div class="col-sm-9">
                                                    <input type="file" class="form-control" id="avatar">
                                                </div>
                                            </div>
                                            <div class="mt-4 mt-lg-0" style="display: flex; align-items: center; justify-content: space-between;">
                                                <b style="margin-left:50px">Avatar hiện tại</b>
                                                <img style="border-radius:0.875rem" src="<?php echo (!empty($currentAvatarPath) ? $currentAvatarPath : 'assets/img/user.png'); ?>" alt="Current Avatar" style="width: 150px; height: 150px; object-fit: cover;">
                                                <button id="submit" type="button" onclick="changeavt()" class="btn btn-primary w-md">Cập nhật</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div>
            </div> <!-- container-fluid -->
        </div>
    </div>
</div>    
<script>
    var baseurl = window.location.protocol + "//" + window.location.host;
    function changepw() {
        var user_password = $('#oldpassword').val();
        var user_new_password = $('#newpassword').val();
        var re_password = $('#repassword').val();
        var fullname = $('#fullname').val();
        var user_email = "<?php echo $_SESSION['email'] ?>";
        if (!user_password) {
            Swal.fire(
                'Thông báo',
                'Bạn chưa nhập mật khẩu cũ',
                'info'
            )
            return;
        }
        if (!user_new_password) {
            Swal.fire(
                'Thông báo',
                'Bạn chưa nhập mật khẩu mới',
                'info'
            )
            return;
        }
        if (user_new_password != re_password) {
            Swal.fire(
                'Thông báo',
                'Mật khẩu xác nhận không trùng khớp',
                'error'
            )
            return;
        }
        $.ajax({
            url: baseurl + '/module/ajax-user.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                type: 'change_password',
                user_password: user_password,
                user_new_password: user_new_password,
                fullname: fullname,
                user_email: user_email
            },
            success: (data) => {
                if (data.error) {
                    Swal.fire(
                        'Thông báo',
                        data.msg,
                        'error'
                    )
                } else {
                    Swal.fire({
                        title: 'Thông báo',
                        text: data.msg,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Đăng nhập lại',
                        cancelButtonText: 'Trang chủ',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'logout.php';
                        } else if (

                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            window.location.href = 'index.php';
                        }
                    })
                }
            }
        })
    }
    function changeavt() {
        var username = $('#username').val();
        var avatar = $('#avatar')[0].files[0];
        if(!avatar){
            Swal.fire("Thông báo", "Bạn chưa chọn file", "error");
            return;
        }
        if (!avatar.type.match('image.*')) {
            Swal.fire("Thông báo", "File is not an image.", "error");
            return;
        }

        if (avatar.size > 3 * 1024 * 1024) {
            Swal.fire("Thông báo", "File is larger than 3MB.", "error");
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
                formData.append('type', 'change_avatar');
                formData.append('username', username);
                formData.append('avatar', avatar);
                $.ajax({
                    url: baseurl + '/module/ajax-user.php',
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
                                location.href='logout.php';
                            }, 500);
                        }
                    }
                });
            }
        });
    }

</script>
</body>

</html>