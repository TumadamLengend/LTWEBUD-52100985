<?php include 'configh/session.php'; ?>
<?php 
$username = $_SESSION['username'];
$id = $_GET['id'];
$showData = "SELECT * FROM songs WHERE id = ?";
$stmt = $conn->prepare($showData);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if($_SESSION['quyen'!=1]){
    if (strcasecmp($username, $row['upuser']) != 0) {
        echo "<script type='text/javascript'>document.location.href='pages-404.php';</script>";
        exit;
    }
}
?>

<head>
    <title>Chỉnh Sửa Thông Tin</title>
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
<style>
    .main-content {
        margin: 0 auto;
        max-width: 1500px; 
        padding: 20px 0;
    }
</style>
<body>
<?php include 'nav.php'; ?>
<!-- Begin page -->
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
                                                    <label for="email" class="col-sm-3 col-form-label">ID</label>
                                                    <div class="col-sm-9">
                                                        <input style=" border:1px solid #000; background-color: #fff;"  type="text" value="<?php echo $row['id'] ?>" class="form-control" id="id"readonly >
                                                    </div>
                                                </div>
                                                <div class="row mb-4">
                                                    <label for="email" class="col-sm-3 col-form-label">Người tải lên</label>
                                                    <div class="col-sm-9">
                                                        <input style=" border:1px solid #000; background-color: #fff;" type="text" value="<?php echo $row['upuser'] ?>" class="form-control" id="title" >
                                                    </div>
                                                </div>
                                                <div class="row mb-4">
                                                    <label for="email" class="col-sm-3 col-form-label">Tên Bài Hát</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" value="<?php echo $row['title'] ?>" class="form-control" id="title" >
                                                    </div>
                                                </div>
                                                <div class="row mb-4">
                                                    <label for="oldpassword" class="col-sm-3 col-form-label">Nghệ Sĩ</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="artist" value="<?php echo $row['artist'] ?>">
                                                    </div>
                                                </div>
                                                <div class="row justify-content-end">
                                                    <div class="col-sm-9">
                                                        <button id="submit" type="button" onclick="editsong(<?php echo $id ?>)" class="btn btn-primary w-md">Submit</button> 
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
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
    function editsong(id) {
        var title =$('#title').val();
        var artist = $('#artist').val();
       
        if (!title) {
            Swal.fire(
                'Thông báo',
                'Bạn chưa nhập tên bài hát',
                'info'
            )
            return;
        }
        if (!artist) {
            Swal.fire(
                'Thông báo',
                'Bạn chưa nhập tên nghệ sĩ',
                'info'
            )
            return;
        }
        $.ajax({
            url: baseurl + '/module/ajax-song.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                type: 'editsong',
                title: title,
                artist: artist,
                id:id
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
        })
    }

</script>
</body>

</html>