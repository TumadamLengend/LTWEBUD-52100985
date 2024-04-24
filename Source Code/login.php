<?php require_once('configh/config.php');
session_start();
if (isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == true) {
    echo "<script type='text/javascript'>document.location.href='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập</title>
    <link rel=icon href="assets/img/fav.ico" sizes="16x16" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                            <div class="mb-2 mb-md-3 text-center">
                                <a href="index.php" class="d-block auth-logo">
                                    <img src="assets/img/fav.ico" alt="" height="100"> <span class="logo-txt"></span>
                                </a>
                            </div>
                            <div class="auth-content my-auto">
                                <div class="text-center mb-3">
                                    <p class="text-muted mt-2">Đăng nhập để tiếp tục!</p>
                                </div>
                                <form class="custom-form mt-4 pt-2" method="post">
                                    <div class="mb-3">
                                        <label class="form-label" for="username">Tên tài khoản/email</label>
                                        <input style="border-radius:10px" type="text" class="form-control" id="username" placeholder="Enter username">
                                    </div>
                                    <div style="border-radius:10px"  class="mb-3">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <label class="form-label" for="password">Mật Khẩu</label>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="">
                                                    <a style="text-decoration:none" href="forgot.php" class="text-muted"><i>Quên Mật Khẩu?</i></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-group auth-pass-inputgroup">
                                            <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" aria-label="Password" aria-describedby="password-addon">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <button class="btn btn-primary w-100 waves-effect waves-light" id="submit" type="button" onclick="login()">Đăng Nhập</button>
                                    </div>
                                </form>
                                <div class="mt-5 text-center">
                                    <p class="text-muted mb-0">Bạn chưa có tài khoản? <a style="text-decoration:none" href="register.php" class="text-primary fw-semibold">Đăng kí ngay</a> </p>
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

    function login() {
        var user_username = $('#username').val();
        var user_password = $('#password').val();
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
        $.ajax({
            url: baseurl + '/module/ajax-user.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                type: 'login',
                user_username: user_username,
                user_password: user_password
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
                        'Đăng nhập thành công',
                        'success'
                    )
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 1000);
                }
            }
        })
    }
</script>
</body>