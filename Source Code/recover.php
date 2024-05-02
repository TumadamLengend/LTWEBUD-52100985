<?php require_once('configh/config.php');
$email = '';
if (isset($_GET['email']) && isset($_GET['token'])) {
    $getemail = $_GET['email'];
    $gettoken = $_GET['token'];

    if (filter_var($getemail, FILTER_VALIDATE_EMAIL) === false) {
        echo "<script type='text/javascript'>document.location.href='pages-404.php';</script>";
        exit;
    } else {
        $res = checkvar($getemail, $gettoken);
        if ($res['code'] == 0) {
            $email = $getemail;
        } else {
            echo "<script type='text/javascript'>document.location.href='pages-404.php';</script>";
            exit;
        }
    }
    $stmt = $conn->prepare("DELETE FROM resettk WHERE DATE_ADD(exp, INTERVAL 10 MINUTE) < NOW()");
    $stmt->execute();
} else {
    echo "<script type='text/javascript'>document.location.href='pages-404.php';</script>";
    exit;
}
?>
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
                                            <input style="border-radius:10px" type="text" class="form-control" id="email" name="email" value="<?= $_GET['email']?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nhập Mật khẩu mới</label>
                                            <input style="border-radius:10px" type="password" class="form-control" id="newpass" name="newpass" placeholder="MK mới">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Xác nhận Mật khẩu mới</label>
                                            <input style="border-radius:10px" type="password" class="form-control" id="cnewpass" name="cnewpass" placeholder="MK mới">
                                        </div>
                                        <div class="mb-3 mt-4">
                                            <button class="btn btn-primary w-100 waves-effect waves-light" type='button' id="submit" onclick="recoverpw()" >Đặt lại</button>
                                        </div>
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
                    <!-- end auth full page content -->
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container fluid -->
    </div>
<!-- JAVASCRIPT -->
<script src="assets/js/vendor.js"></script>
<script>
      var baseurl = window.location.protocol + "//" + window.location.host;

    function recoverpw() {
        var email = $('#email').val();
        var newpass= $('#newpass').val();
        var cnewpass= $('#cnewpass').val();
        if (!email) {
            Swal.fire({
                title: 'Thông báo',
                text: 'Bạn chưa nhập email',
                icon: 'info',
                allowOutsideClick: false
            })
            return;
        }
        if(cnewpass != newpass){
            Swal.fire({
                title: 'Thông báo',
                text: 'Mật khẩu xác nhận không trùng khớp',
                icon: 'error',
                allowOutsideClick: false
            })
            return;
        }
        $.ajax({
            url: baseurl + '/module/ajax-user.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                type: 'recover_pw',
                email: email,
                newpass: newpass
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