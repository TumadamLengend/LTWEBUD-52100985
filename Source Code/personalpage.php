<?php include 'configh/session.php'; ?>
<?php 
if(isset($_GET['username'])) {
    $username = $_GET['username'];
    $stmt = $conn->prepare("SELECT * FROM tai_khoan WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 0){
        echo "<script type='text/javascript'>document.location.href='pages-404.php';</script>";
        exit;
    }else{
        $curruser=$_SESSION['username'];
        $user = $result->fetch_assoc();
        //đếm sl bài hát
        $stmt = $conn->prepare("SELECT COUNT(*) as song_count FROM songs WHERE upuser = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $song_count = $result->fetch_assoc()['song_count'];
    }
}else{
    echo "<script type='text/javascript'>document.location.href='pages-404.php';</script>";
    exit;
}

?>
<style>
    .rate-star:hover {
        color: #000000;
    }
</style>
<head>
    <title>Thông Tin Người dùng</title>
    <link rel=icon href="assets/img/fav.ico" sizes="16x16" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.1/css/boxicons.min.css">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/jquery.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="assets/css/mainpage.css">
</head>

<body>
<?php include 'nav.php'; ?>
<div id="layout-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="<?php echo (!empty($user['pathavt']) ? $user['pathavt'] : 'assets/img/user.png'); ?>" class="rounded-circle mb-3" alt="User Avatar" style="width: 150px; height: 150px; object-fit: cover;">
                        <h2 class="card-title">Thông Tin Người dùng</h2>
                        <p class="card-text"><strong>Username:</strong> <?php echo $user['username']; ?></p>
                        <p class="card-text"><strong>Họ và tên:</strong> <?php echo $user['fullname']; ?></p>
                        <p class="card-text"><strong>Đánh giá:</strong> <?php echo $user['star']; ?> <i class="fas fa-star" style="color: orange;"></i></p>
                        <p class="card-text"><strong>Số lượng bài hát tải lên:</strong> <?php echo $song_count; ?></p>
                        <strong>Đánh giá của bạn</strong>
                        <div id="rating">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star rate-star" data-rate="<?php echo $i; ?>" style="cursor: pointer; color: orange;"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var baseurl = window.location.protocol + "//" + window.location.host;
    $('.rate-star').click(function() {
        var rate = $(this).data('rate');
        var username = "<?php echo $user['username']; ?>"; 
        var curruser = "<?php echo $curruser; ?>";
        Swal.fire({
            title: 'Bạn có chắc chắn muốn đánh giá người dùng này ' + rate + ' sao?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Có',
            cancelButtonText: 'Không'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: baseurl + '/module/ajax-user.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: { star: rate, type: 'updatestar', username: username, curruser: curruser}, 
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
                                window.location.reload();
                            }, 500);
                        }
                    }
                });
            }
        })
    });
</script>
</body>
</html>