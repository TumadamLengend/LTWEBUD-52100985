<?php include 'configh/session.php'; ?>
<?php
if($_SESSION['quyen'] != 1) {
    $stmt=$conn->prepare("SELECT * from songs where upuser=?");
    $stmt->bind_param("s",$_SESSION['username']);
    $stmt->execute();
    $showdata=$stmt->get_result();
}else{
    $stmt=$conn->prepare("SELECT * from songs");
    $stmt->execute();
    $showdata=$stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Danh Sách Bài Hát</title>
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
                    <th class="text-center">Tên bài hát</th>
                    <th class="text-center">Tác giả</th>
                    <th class="text-center">Ngày tải lên</th>
                    <th class="text-center">Người tải lên</th>
                    <th class="text-center">Chỉnh sửa</th>
                    <th class="text-center">Xoá bỏ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($song = $showdata->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center"><?php echo $song['id']; ?></td>
                        <td class="text-center"><?php echo $song['title']; ?></td>
                        <td class="text-center"><?php echo $song['artist']; ?></td>
                        <td class="text-center"><?php echo $song['uploaded_at']; ?></td>
                        <td class="text-center"><?php echo $song['upuser']; ?></td>
                        <td class="text-center">
                            <a class='btn btn-success' href='editsong.php?id=<?php echo $song['id'] ?>'>
                                <i class='bx bx-edit label-icon'></i> Sửa
                            </a>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-danger" onclick="deleteSong(<?php echo $song['id']; ?>)"><i class='bx bx-trash label-icon'></i> Xoá</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <!-- JS, Popper.js, and jQuery -->
    <script src="assets/js/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
    var baseurl = window.location.protocol + "//" + window.location.host;
    function deleteSong(id) {
        Swal.fire({
            title: 'Xác nhận',
            icon: 'warning',
            text: `Bạn chắc chắn muốn xoá bài hát có id ${id} ?`,
            showCancelButton: true,
            confirmButtonText: "Xác nhận",
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: baseurl + '/module/ajax-song.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        type: 'delsong',
                        key_id: id
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