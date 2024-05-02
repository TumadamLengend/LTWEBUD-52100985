<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <title>Đăng Kí</title>
    <link rel=icon href="assets/img/fav.ico" sizes="16x16" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="assets/js/swal.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/vendor.js"></script>
    <link rel="stylesheet" href="assets/css/mainpage.css">
</head>
<body>
<div class="auth-page">
    <div class="container-fluid p-0">
        <div class="row g-0 justify-content-center">
            <div class="col-xxl-3 col-lg-4 col-md-5">
                <div class="auth-full-page-content d-flex p-sm-5 p-4">
                    <div class="w-100">
                        <div class="d-flex flex-column h-100">
                            <div class="mb-4 mb-md-5 text-center">
                                <a href="index.php" class="d-block auth-logo">
                                    <img src="assets/img/fav.ico" alt="" height="100"> <span class="logo-txt"></span>
                                </a>
                            </div>
                            <div class="auth-content my-auto">
                                <div class="text-center">
                                    <h5 class="mb-0">Đăng kí tài Khoản</h5>
                                    <p class="text-muted mt-2"></p>
                                </div>
                                <form class="needs-validation custom-form mt-4 pt-2" method="post">
                                    <div class="mb-3">
                                        <label for="useremail" class="form-label">Email</label>
                                        <input style="border-radius:10px" type="email" class="form-control" id="useremail" placeholder="Nhập email" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="username" class="form-label">Tên tài khoản</label>
                                        <input style="border-radius:10px" type="text" class="form-control" id="username" placeholder="Nhập username" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Họ Và Tên</label>
                                        <input style="border-radius:10px" type="text" class="form-control" id="fullname" placeholder="Họ và tên của bạn" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="userpassword" class="form-label">Mật khẩu</label>
                                        <input style="border-radius:10px" type="password" class="form-control" id="userpassword" placeholder="Nhập password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="userpassword">Xác nhận mật khẩu</label>
                                        <input style="border-radius:10px" type="password" class="form-control" id="confirm_password" placeholder="Xác nhận mật khẩu" required>
                                    </div>
                                        <div class="mb-3 justify-content-center">
                                    </div>
                                    <div class="mb-3">
                                        <button class="btn btn-primary w-100 waves-effect waves-light" id="submit" onclick="reg()" type="button">Đăng kí</button>
                                    </div>
                                </form>
                                <div class="mt-5 text-center">
                                    <p class="text-muted mb-0">Bạn đã có tài khoản? <a style="text-decoration:none" href="login.php" class="text-primary fw-semibold"> Đăng nhập ngay </a> </p>
                                </div>
                            </div>
                            <div class="mt-4 mt-md-5 text-center">
                                <p class="mb-0">© <script>
                                        document.write(new Date().getFullYear())
                                    </script>Made By Phát Phạm</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var baseurl = window.location.protocol + "//" + window.location.host;
    function reg() {
        var user_username = $('#username').val();
        var fullname = $('#fullname').val();
        var user_password = $('#userpassword').val();
        var user_email = $('#useremail').val();
        var co_password = $('#confirm_password').val();
        if (!user_email) {
            Swal.fire(
                'Thông báo',
                'Bạn chưa nhập email',
                'info'
            )
            return;
        }
        if (!user_username) {
            Swal.fire(
                'Thông báo',
                'Bạn chưa nhập username',
                'info'
            )
            return;
        }
        if (!user_password) {
            Swal.fire(
                'Thông báo',
                'Bạn chưa nhập mật khẩu',
                'info'
            )
            return;
        }
        if (user_password != co_password) {
            Swal.fire(
                'Thông báo',
                'Xác nhận lại mật khẩu',
                'info'
            )
            return;
        }
        $.ajax({
            url: baseurl + '/module/ajax-user.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                type: 'register',
                user_username: user_username,
                user_password: user_password,
                fullname: fullname,
                user_email: user_email,
            },
            beforeSend: function() {
                wait('#submit', false);
            },
            success: (data) => {
                if (data.error) {
                    Swal.fire(
                        'Thông báo',
                        data.msg,
                        'error'
                    )
                } else {
                    Swal.fire(
                        'Thông báo',
                        data.msg,
                        'success'
                    )
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 1000);
                }
            }
        })
    }
</script>
</body>
</html>