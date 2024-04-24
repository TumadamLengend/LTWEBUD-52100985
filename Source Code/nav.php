<?php include 'configh/session.php'; ?>
<nav class="navbar navbar-expand-lg custom-navbar">
    <a class="navbar-brand" href="index.php">
        <img style="margin-left:5px" src="assets/img/fav.ico" width="30" height="30" alt=""><b style="color:white">MUSIC SHARING</b>
    </a>
    <button style="margin-right:10px;margin-bottom:5px" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars" style="color:white;"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
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
                        <a class="dropdown-item" href="personalpage.php?username=<?= $_SESSION['username']?>"><i class="fas fa-user"></i>Trang cá nhân</a>
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