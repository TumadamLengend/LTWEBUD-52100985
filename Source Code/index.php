<?php
session_start();
require_once('configh/config.php');

// Lấy các tham số từ URL
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 3;
$offset = ($page > 1) ? ($page - 1) * $perPage : 0;
$genre = isset($_GET['genre']) ? $_GET['genre'] : '';

// Chuẩn bị câu lệnh SQL dựa trên việc có chỉ định thể loại hay không
if ($genre) {
    $stmt = prepareStatementWithGenre($conn, $genre, $search, $perPage, $offset);
} else {
    $stmt = prepareStatementWithoutGenre($conn, $search, $perPage, $offset);
}

// Thực thi câu lệnh và lấy kết quả
$stmt->execute();
$vipres = $stmt->get_result();

// Chuẩn bị câu lệnh SQL để lấy tổng số bài hát
if ($genre) {
    $stmt = prepareCountStatementWithGenre($conn, $genre, $search);
} else {
    $stmt = prepareCountStatementWithoutGenre($conn, $search);
}

// Thực thi câu lệnh và lấy tổng số bài hát
$stmt->execute();
$result = $stmt->get_result();
$totalSongs = $result->fetch_assoc()['total'];
$totalPages = ceil($totalSongs / $perPage);

// Hàm chuẩn bị câu lệnh SQL khi một thể loại được chỉ định
function prepareStatementWithGenre($conn, $genre, $search, $perPage, $offset) {
    $stmt = $conn->prepare("SELECT * FROM songs WHERE theloai = ? AND title LIKE ? ORDER BY uploaded_at DESC LIMIT ? OFFSET ?");
    $searchParam = "%" . $search . "%";
    $stmt->bind_param("ssii", $genre, $searchParam, $perPage, $offset);
    return $stmt;
}

// Hàm chuẩn bị câu lệnh SQL khi không có thể loại nào được chỉ định
function prepareStatementWithoutGenre($conn, $search, $perPage, $offset) {
    $stmt = $conn->prepare("SELECT * FROM songs WHERE title LIKE ? ORDER BY uploaded_at DESC LIMIT ? OFFSET ?");
    $searchParam = "%" . $search . "%";
    $stmt->bind_param("sii", $searchParam, $perPage, $offset);
    return $stmt;
}

// Hàm chuẩn bị câu lệnh SQL để lấy tổng số bài hát khi một thể loại được chỉ định
function prepareCountStatementWithGenre($conn, $genre, $search) {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM songs WHERE theloai = ? AND title LIKE ?");
    $searchParam = "%" . $search . "%";
    $stmt->bind_param("ss", $genre, $searchParam);
    return $stmt;
}

// Hàm chuẩn bị câu lệnh SQL để lấy tổng số bài hát khi không có thể loại nào được chỉ định
function prepareCountStatementWithoutGenre($conn, $search) {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM songs WHERE title LIKE ?");
    $searchParam = "%" . $search . "%";
    $stmt->bind_param("s", $searchParam);
    return $stmt;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Music Sharing</title>
    <link rel=icon href="assets/img/fav.ico" sizes="16x16" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/mainpage.css">
</head>

<body>

<nav class="navbar navbar-expand-lg custom-navbar">
    <a class="navbar-brand" href="index.php">
        <img style="margin-left:5px" src="assets/img/fav.ico" width="30" height="30" alt=""><b style="color:white">MUSIC SHARING</b>
    </a>
    <button style="margin-right:10px;margin-bottom:5px" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars" style="color:white;"></i>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <form action="index.php" method="get" class="d-flex">
            <input class="form-control me-2" type="search" name="search" placeholder="Nhập tên bài hát" aria-label="Search" value="<?php echo htmlspecialchars($search); ?>">
            <button style="margin-right:10px" class="btn btn-outline-success" type="submit"><i class="fas fa-search"></i></button>
        </form>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="genreDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Thể Loại
            </button>
            <div class="dropdown-menu" aria-labelledby="genreDropdown">
                <a class="dropdown-item" href="?genre=edm">EDM</a>
                <a class="dropdown-item" href="?genre=english">English</a>
                <a class="dropdown-item" href="?genre=heroic">Heroic</a>
                <a class="dropdown-item" href="?genre=other">Other</a>
                <a class="dropdown-item" href="?genre=anime">Nhạc Anime</a>
            </div>
        </div>
        <div class="navbar-nav ms-auto d-flex flex-wrap"> 
        <a style="color:#fff" class="nav-item nav-link mb-2 mb-lg-0" href="ranking.php"><i class="fas fa-trophy"></i>Xếp hạng</a>
            <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                <a style="color:#fff" class="nav-item nav-link mb-2 mb-lg-0" href="upload.php"><i class="fas fa-upload"></i>Tải Lên</a>
                <div class="nav-item dropdown">
                    <a style="color:#fff" class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user"></i><?php echo $_SESSION['username']; ?>
                    </a>
                    <div class="dropdown-menu">
                        <?php if ($_SESSION['quyen'] == 1): ?>
                            <a class="dropdown-item" href="admin-listuser.php"><i class="fas fa-users-cog"></i> Quản lí user</a>
                        <?php endif; ?>
                        <a class="dropdown-item" href="listsong.php"><i class="fas fa-music"></i> Bài hát đã đăng</a>
                        <a class="dropdown-item" href="account.php"><i class="fas fa-user-cog"></i> Cài đặt TK</a>
                        <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                    </div>
                </div>
            <?php else: ?>
                <a style="color:#fff" class="nav-item nav-link mb-2 mb-lg-0" href="upload.php"><i class="fas fa-upload"></i>Tải Lên</a>
                <a style="color:#fff" class="nav-item nav-link mb-2 mb-lg-0" href="login.php"><i class="fas fa-sign-in-alt"></i>Đăng nhập</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center mb-6">Tải Lên Gần Đây</h1>
                <div class="row">
                    <?php if ($vipres->num_rows > 0): ?>
                        <?php while ($viprow = $vipres->fetch_assoc()): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card shadow custom-rounded">
                                    <img  width="400" height="300" src="<?= $viprow['pathimg'] ?>" class="card-img-top" alt="Song image">
                                    <div class="card-body">
                                        <h5 class="card-title" style="text-align: center;"><?= $viprow['title'] ?></h5>
                                        <p class="card-text" style="text-align: center;"><?= $viprow['artist'] ?></p>
                                        <p class="card-text">
                                        <small class="text-muted">
                                            Tải lên bởi: 
                                            <?php 
                                                $uploader_username = $viprow['upuser']; 
                                                $stmt = $conn->prepare("SELECT * from tai_khoan where username = ?");
                                                $stmt->bind_param("s", $uploader_username);
                                                $stmt->execute();
                                                $uploader_result = $stmt->get_result();
                                                $uploader_info = $uploader_result->fetch_assoc();
                                                echo "<strong><em><a href='personalpage.php?username=" . $uploader_username . "'>" . $uploader_info['fullname'] . "</a></em></strong>";
                                            ?>
                                            <br>
                                            Thể loại: <?= $viprow['theloai'] ?>
                                            <br>
                                            Lượt nghe: <?= $viprow['play_count'] ?>
                                        </small>
                                        </p>
                                    </div>
                                    <audio controls class="card-audio" style="width: calc(100% - 20px); margin: 0 10px 10px;" data-id="<?= $viprow['id'] ?>">
                                        <source src="<?= $viprow['songdir'] ?>" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-md-12">
                            <p class="text-center">Chưa có ai tải lên bài hát, bạn hãy là người đầu tiên làm điều đó!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'current-page' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
    <script src="assets/js/jquery.js"></script>
    <script>
        var baseurl = window.location.protocol + "//" + window.location.host;
        var lastPlayed = {};
        $(document).ready(function() {
            $('audio').on('play', function() {
                var songId = $(this).data('id');
                var now = Date.now();
                if (!lastPlayed[songId] || now - lastPlayed[songId] >= 30000) { 
                    lastPlayed[songId] = now;
                    $.ajax({
                        url: baseurl + '/module/ajax-playcount.php',
                        type: 'POST',
                        data: { id: songId, type: 'updatecount' },
                        success: function(data) {
                            console.log('Play count increased');
                        }
                    });
                }
            });
        });
    </script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>