<?php include 'configh/session.php'; 
if ($_SESSION['quyen'] != 1) {
    echo "<script type='text/javascript'>document.location.href='pages-404.php';</script>";
    exit;
}
$showData = "
    SELECT tai_khoan.*, COUNT(songs.id) as song_count 
    FROM tai_khoan 
    LEFT JOIN songs ON tai_khoan.username = songs.upuser 
    WHERE tai_khoan.id <> 1 
    GROUP BY tai_khoan.id 
    ORDER BY tai_khoan.ngay_tao DESC
";
$stmt = $conn->prepare($showData);
$stmt->execute();
$result = $stmt->get_result();
$arrShow = array();
while ($row = $result->fetch_assoc()) {
    $arrShow[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Danh Sách Member</title>
    <link rel=icon href="assets/img/fav.ico" sizes="16x16" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/mainpage.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.1/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
</head>
<body>
<?php include 'nav.php'; ?>
    <div class="container">
        <table class="table table-dark">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Username</th>
                    <th class="text-center">Fullname</th>
                    <th class="text-center">Lượt truy cập</th>
                    <th class="text-center">Số bài hát</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arrShow as $arr): ?>
                    <tr>
                        <td class="text-center"><?php echo $arr['id']; ?></td>
                        <td class="text-center"><?php echo $arr['username']; ?></td>
                        <td class="text-center"><?php echo $arr['fullname']; ?></td>
                        <td class="text-center"><?php echo $arr['truy_cap']; ?></td>
                        <td class="text-center"><?php echo $arr['song_count']; ?></td>
                        <td class="text-center">
                            <?php if ($arr['trang_thai'] == 1): ?>
                                <button class="btn btn-danger" onclick="lock(<?php echo $arr['id']; ?>)"><i class='fas fa-lock'></i> Khoá</button>
                            <?php else: ?>
                                <button class="btn btn-success" onclick="unlock(<?php echo $arr['id']; ?>)"><i class='fas fa-unlock'></i> Mở khoá</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="assets/js/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
    var baseurl = window.location.protocol + "//" + window.location.host;
    function lock(id) {
        Swal.fire({
            title: 'Xác nhận',
            icon: 'warning',
            text: `Bạn chắc chắn muốn khoá người dùng có id ${id} ?`,
            showCancelButton: true,
            confirmButtonText: "Xác nhận",
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: baseurl + '/module/ajax-user.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        type: 'lock',
                        id: id
                    },
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
                                location.reload();
                            }, 500);
                        }
                    }
                });
            }
        });
    }
    function unlock(id) {
        Swal.fire({
            title: 'Xác nhận',
            icon: 'warning',
            text: `Bạn chắc chắn muốn mở khoá người dùng có id ${id} ?`,
            showCancelButton: true,
            confirmButtonText: "Xác nhận",
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: baseurl + '/module/ajax-user.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        type: 'unlock',
                        id: id
                    },
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
                                location.reload();
                            }, 500);
                        }
                    }
                });
            }
        });
    }
</script>
</html>
