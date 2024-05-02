<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Khôi Phục</title>
    <link rel=icon href="assets/img/fav.ico" sizes="16x16" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="assets/js/swal.js"></script>
    <script src="assets/js/jquery.js"></script>
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
                                    <img src="assets/img/fav.ico" alt="" height="100">
                                </a>
                            </div>
                            <div class="auth-content my-auto">
                                <div class="text-center">
                                    <h5 class="mb-0">Đặt lại mật khẩu</h5>
                                </div>
                                
                                <form class="custom-form mt-4">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input style="border-radius:10px" type="text" class="form-control" id="email" name="email" placeholder="Enter email">
                                    </div>
                                    <div class="mb-3 mt-4">
                                        <button class="btn btn-primary w-100 waves-effect waves-light" type='button' id="submit" onclick="recoverpw()" >Đặt lại</button>
                                    </div>
                                <div id="alert-message" class="alert alert-success text-center" style="display: none;">Vui lòng kiểm tra email của bạn(Nếu nhập đúng)</div>
                                </form>

                                <div class="mt-5 text-center">
                                    <p class="text-muted mb-0">Bạn đã nhớ Tài khoản ? <a style="text-decoration:none" href="login.php" class="text-primary fw-semibold"> Đăng nhập </a> </p>
                                </div>
                            </div>
                            <div class="mt-4 mt-md-5 text-center">
                                <p class="mb-0">© <script>
                                    document.write(new Date().getFullYear())
                                </script> Made By Phát Phạm</p>
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
    function recoverpw() {
        var email = $('#email').val();
        if (!email) {
            Swal.fire({
                title: 'Thông báo',
                text: 'Bạn chưa nhập email',
                icon: 'info',
                allowOutsideClick: false
            })
            return;
        }
        $.ajax({
            url: baseurl + '/module/ajax-user.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                type: 'forgot',
                email: email
            },
            beforeSend: function() {
                wait('#submit', false);
            },
            complete: function() {
                wait('#submit', true, 'Gửi lại email');
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
    function wait(t, e, n) {
        return e ? $(t).prop("disabled", !1).html(n) : $(t).prop("disabled", !0).html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...')
    }
</script>
</body>