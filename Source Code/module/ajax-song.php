<?php
require_once("../configh/session.php");
require_once("../configh/config.php");
if ($_REQUEST) {
    $return = array(
        'error' => 0
    );

    $type = $_REQUEST['type'];

    if ($type === 'upfile') {
        $stmt = $conn->prepare("SELECT title FROM songs WHERE title = ?");
        $stmt->bind_param("s", $_POST['title']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $return['error'] = 1;
            $return['msg'] = "Tên bài hát đã tồn tại.";
            die(json_encode($return));
        }
        $relative_path = 'songs/' . basename($_FILES["musicFile"]["name"]);
        $relative_path_img = 'assets/songsimg/' . basename($_FILES["imageFile"]["name"]);

        $stmt = $conn->prepare("SELECT songdir FROM songs WHERE songdir = ?");
        $stmt->bind_param("s", $relative_path);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $return['error'] = 1;
            $return['msg'] = "File nhạc đã tồn tại.";
            die(json_encode($return));
        }

        $stmt = $conn->prepare("SELECT pathimg FROM songs WHERE pathimg = ?");
        $stmt->bind_param("s", $relative_path_img);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $return['error'] = 1;
            $return['msg'] = "File ảnh đã tồn tại.";
            die(json_encode($return));
        }
        $target_dir = "../songs/";
        $target_file = $target_dir . basename($_FILES["musicFile"]["name"]);
        $target_dir_img = "../assets/songsimg/";
        $target_file_img = $target_dir_img . basename($_FILES["imageFile"]["name"]);
        if (move_uploaded_file($_FILES["musicFile"]["tmp_name"], $target_file) && move_uploaded_file($_FILES["imageFile"]["tmp_name"], $target_file_img)) {
            $relative_path = 'songs/' . basename($_FILES["musicFile"]["name"]);
            $relative_path_img = 'assets/songsimg/' . basename($_FILES["imageFile"]["name"]);
            $stmt = $conn->prepare("INSERT INTO songs (title, artist, songdir, pathimg, uploaded_at, upuser, fullname, theloai) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)");
            $stmt->bind_param("sssssss", $_POST['title'], $_POST['artist'], $relative_path, $relative_path_img, $_SESSION['username'], $_POST['fullname'], $_POST['genre']);
            if ($stmt->execute()) {
                $return['msg'] = "Đã tải lên thành công.";
            } else {
                $return['msg'] = "Database error: " . $stmt->error;
            }
            die(json_encode($return));
        } else {
            $return['msg'] = "Có lỗi xảy ra,vui lòng thao tác lại.";
            die(json_encode($return));
        }
    }

    if ($type === 'delsong') {
        $id = mysqli_real_escape_string($conn, $_POST['key_id']);
        if($_SESSION['quyen']==1){
            $stmt = $conn->prepare("SELECT songdir, pathimg FROM songs WHERE id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $song = $result->fetch_assoc();
            $songdir = '../' . $song['songdir'];
            $pathimg = '../' . $song['pathimg'];
            $stmt = $conn->prepare("DELETE FROM songs WHERE id = ? ");
            $stmt->bind_param("s", $id);
            $resultTbldebkey = $stmt->execute();
            if ($resultTbldebkey) {
                if (file_exists($songdir)) {
                    unlink($songdir);
                }
                if (file_exists($pathimg)) {
                    unlink($pathimg);
                }
                $return['msg'] = 'Bạn đã xoá bài hát có id ' . $id . ' thành công.';
                die(json_encode($return));
            } else {
                $return['error'] = 1;
                $return['msg'] = 'Bạn không có quyền xoá bài hát có id ' . $id;
                die(json_encode($return));
            }
        }else{
            $stmt = $conn->prepare("SELECT songdir, pathimg FROM songs WHERE id = ? and upuser=?");
            $stmt->bind_param("ss", $id, $_SESSION['username']);
            $stmt->execute();
            $result = $stmt->get_result();
            $song = $result->fetch_assoc();
            $songdir = '../' . $song['songdir'];
            $pathimg = '../' . $song['pathimg'];
            $stmt = $conn->prepare("DELETE FROM songs WHERE id = ? and upuser=?");
            $stmt->bind_param("ss", $id, $_SESSION['username']);
            $resultTbldebkey = $stmt->execute();
            if ($resultTbldebkey) {
                if (file_exists($songdir)) {
                    unlink($songdir);
                }
                if (file_exists($pathimg)) {
                    unlink($pathimg);
                }
                $return['msg'] = 'Bạn đã xoá bài hát có id ' . $id . ' thành công.';
                die(json_encode($return));
            } else {
                $return['error'] = 1;
                $return['msg'] = 'Bạn không có quyền xoá bài hát có id ' . $id;
                die(json_encode($return));
            }
        }
    }

    if ($type === 'editsong') {
        $title = $_POST['title'];
        $artist =$_POST['artist'];
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE songs SET title = ?, artist = ? WHERE id = ?");
        $stmt->bind_param("sss", $title, $artist, $id);
        if ($stmt->execute()) {
            $return['msg'] = 'Cập nhật thành công.';
            die(json_encode($return));
        } else {
            $return['error'] = 1;
            $return['msg'] = 'Đã xảy ra lỗi khi cập nhật, vui lòng thử lại sau.';
            die(json_encode($return));
        }
    }
} 
