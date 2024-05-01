<?php include 'configh/session.php'; ?>
<?php
$stmt = $conn->prepare("SELECT fullname, upuser, COUNT(*) as song_count FROM songs GROUP BY upuser ORDER BY song_count DESC LIMIT 10");
$stmt->execute();
$users = $stmt->get_result();

$stmt = $conn->prepare("SELECT * FROM songs ORDER BY play_count DESC LIMIT 10");
$stmt->execute();
$songs = $stmt->get_result();

$stmt = $conn->prepare("SELECT * from tai_khoan where email = ?");
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$huhu = $stmt->get_result();
$haha = $huhu->fetch_assoc();

$stmt = $conn->prepare("SELECT * FROM tai_khoan ORDER BY star DESC LIMIT 10");
$stmt->execute();
$top_rated_users = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel=icon href="assets/img/fav.ico" sizes="16x16" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <meta charset="UTF-8">
    <title>Xếp Hạng</title>
    <link rel="stylesheet" href="assets/css/mainpage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="assets/js/jquery.js"></script>
</head>
<body>
<?php include 'nav.php'; ?>
    <div class="container">
        <h1 class="text-center">Top 10 Người Dùng Tải Lên Nhiều Nhất</h1>
        <table class="table table-dark">
            <thead>
                <tr>
                    <th class="text-center">Hạng</th>
                    <th class="text-center">Họ Tên</th>
                    <th class="text-center">Username</th>
                    <th class="text-center">Số lượng bài hát</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="text-center">
                            <?php 
                                if ($rank == 1) {
                                    echo '<i class="fas fa-medal text-warning">1st</i>';
                                } elseif ($rank == 2) {
                                    echo '<i class="fas fa-medal text-primary">2nd</i>';
                                } elseif ($rank == 3) {
                                    echo '<i class="fas fa-medal text-success">3rd</i>';
                                } else {
                                    echo $rank;
                                }
                                $rank++;
                            ?>
                        </td>
                        <td class="text-center"><?php echo $user['fullname']; ?></td>
                        <td class="text-center"><?php echo $user['upuser']; ?></td>
                        <td class="text-center"><?php echo $user['song_count']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <br>
        <h1 class="text-center">Top 10 Người Dùng Được Đánh Giá Cao Nhất</h1>
        <table class="table table-dark">
            <thead>
                <tr>
                    <th class="text-center">Hạng</th>
                    <th class="text-center">Username</th>
                    <th class="text-center">Họ Tên</th>
                    <th class="text-center">Số sao</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; ?>
                <?php foreach ($top_rated_users as $user): ?>
                    <tr>
                        <td class="text-center">
                            <?php 
                                if ($rank == 1) {
                                    echo '<i class="fas fa-medal text-warning">1st</i>';
                                } elseif ($rank == 2) {
                                    echo '<i class="fas fa-medal text-primary">2nd</i>';
                                } elseif ($rank == 3) {
                                    echo '<i class="fas fa-medal text-success">3rd</i>';
                                } else {
                                    echo $rank;
                                }
                                $rank++;
                            ?>
                        </td>
                        <td class="text-center"><?php echo $user['username']; ?></td>
                        <td class="text-center"><?php echo $user['fullname']; ?></td>
                        <td class="text-center"><?php echo $user['star']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <br>
        <h1 class="text-center">Top 10 Bài Hát Được Nghe Nhiều Nhất</h1>
        <table class="table table-dark">
            <thead>
                <tr>
                    <th class="text-center">Hạng</th>
                    <th class="text-center">Tên bài hát</th>
                    <th class="text-center">Thể Loại</th>
                    <th class="text-center">Lượt nghe</th>
                    <th class="text-center">Tải Lên Bởi</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; ?>
                <?php foreach ($songs as $song): ?>
                    <tr>
                        <td class="text-center">
                            <?php 
                                if ($rank == 1) {
                                    echo '<i class="fas fa-medal text-warning">1st</i>';
                                } elseif ($rank == 2) {
                                    echo '<i class="fas fa-medal text-primary">2nd</i>';
                                } elseif ($rank == 3) {
                                    echo '<i class="fas fa-medal text-success">3rd</i>';
                                } else {
                                    echo $rank;
                                }
                                $rank++;
                            ?>
                        </td>
                        <td class="text-center"><?php echo $song['title']; ?></td>
                        <td class="text-center"><?php echo $song['theloai']; ?></td>
                        <td class="text-center"><?php echo $song['play_count']; ?></td>
                        <td class="text-center"><?php echo $song['upuser']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="assets/js/bootstrap.js"></script>
</body>
</html>