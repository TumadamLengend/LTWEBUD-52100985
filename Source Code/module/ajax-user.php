<?php
require_once ("../configh/config.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once ('../vendor/autoload.php');
function sendresetpw($email,$token){
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'duyphat8d@gmail.com';                     //SMTP username
        $mail->Password   = 'pwjjdeogwslkirpe';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('duyphat8d@gmail.com', 'APIKey');
        $mail->addAddress($email, 'Người nhận');     //Add a recipient
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Khoi Phuc TK';
        $mail->Body = 'Click <a href="localhost/recover.php?email=' . $email . '&token=' . $token . '">vào đây</a> để đặt lại MK';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->send();
       return true;
    } catch (Exception $e) {
        return false;
    }
}
if ($_REQUEST) {
    $return = array(
        'error' => 0
    );
    $type = $_REQUEST['type'];

    if ($type === 'login') {
        $user_username = mysqli_real_escape_string($conn, $_POST['user_username']);
        $user_password = md5(mysqli_real_escape_string($conn, $_POST['user_password']));
        if (!filter_var($user_username, FILTER_VALIDATE_EMAIL)) {
            $stmt = $conn->prepare("SELECT id, username, fullname, email, mat_khau, quyen, truy_cap, trang_thai FROM tai_khoan WHERE username = ?");
            $stmt->bind_param("s", $user_username);
            $stmt->execute();
            $result = $stmt->get_result();
            $check_username = $result->fetch_assoc();
            
            if ($check_username == 0) {
                $return['error'] = 1;
                $return['msg']   = 'Tên đăng nhập không tồn tại';
                die(json_encode($return));
            } else if ($check_username['mat_khau'] != $user_password) {
                $return['error'] = 1;
                $return['msg'] = 'Sai mật khẩu !';
                die(json_encode($return));
            } else if ($check_username['trang_thai'] == 0) {
                $return['error'] = 1;
                $return['msg'] = 'Tài khoản của bạn bị khóa. Lí do: Spam !';
                die(json_encode($return));
            } else {
                $email = $check_username['email'];
                $fullname = $check_username['fullname'];
                $quyen = $check_username['quyen'];
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $user_username;
                $_SESSION['fullname'] = $fullname;
                $_SESSION['quyen'] = $quyen;
                $access = $check_username['truy_cap'] + 1;
                $stmt = $conn->prepare("UPDATE tai_khoan SET truy_cap = ? WHERE email = ?");
                $stmt->bind_param("is", $access, $email);
                $stmt->execute();
                $return['msg'] = 'Đăng nhập thành công';
                die(json_encode($return));
            }
        } else {
            $stmt = $conn->prepare("SELECT id, username, email, mat_khau,  truy_cap, trang_thai FROM tai_khoan WHERE email = ?");
            $stmt->bind_param("s", $user_username);
            $stmt->execute();
            $result = $stmt->get_result();
            $check_email = $result->fetch_assoc();
            if ($check_email == 0) {
                $return['error'] = 1;
                $return['msg']   = 'Email không tồn tại';
                die(json_encode($return));
            } else if ($check_email['mat_khau'] != $user_password) {
                $return['error'] = 1;
                $return['msg'] = 'Sai mật khẩu !';
                die(json_encode($return));
            } else if ($check_email['trang_thai'] == 0) {
                $return['error'] = 1;
                $return['msg'] = 'Tài khoản của bạn chưa được kích hoạt hoặc đã bị khoá !';
                die(json_encode($return));
            } else {
                $email = $check_email['email'];
                $usr = $check_email['username'];
                $quyen = $check_username['quyen'];
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $usr;
                $_SESSION['fullname'] = $fullname;
                $_SESSION['quyen'] = $quyen;
                $access = $check_email['truy_cap'] + 1;
                $stmt = $conn->prepare("UPDATE tai_khoan SET truy_cap = ? WHERE email = ?");
                $stmt->bind_param("is", $access, $email);
                $stmt->execute();
                $return['msg'] = 'Đăng nhập thành công';
                die(json_encode($return));
            }
        }
    }

    if ($type === 'register') {
        $user_username = htmlspecialchars(addslashes($_POST['user_username']));
        $fullname =htmlspecialchars(addslashes($_POST['fullname']));
        $charCount = count_chars($user_username, 1);
        foreach ($charCount as $char => $count) {
            if ($count / strlen($user_username) > 0.5) {
                $return['error'] = 1;
                $return['msg']   = 'Tên đăng nhập có dấu hiệu spam.';
                die(json_encode($return));
            }
        }
        $user_email    = htmlspecialchars(addslashes($_POST['user_email']));
        $user_password = htmlspecialchars(addslashes($_POST['user_password']));
        if(strlen($user_password)<5){
            $return['error'] = 1;
            $return['msg'] = 'Mật khẩu phải từ 5 kí tự.';
            die(json_encode($return));
        }
        $user_password = md5($user_password);
        $access = 0;
        $date_create = date("Y-m-d H:i:s");
        $stmt = $conn->prepare("SELECT id FROM tai_khoan WHERE username = ?");
        $stmt->bind_param("s", $user_username);
        $stmt->execute();
        $check_username_available = $stmt->get_result()->num_rows;

        $stmt = $conn->prepare("SELECT id FROM tai_khoan WHERE email = ?");
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $check_email_available = $stmt->get_result()->num_rows;
        if (strlen($user_email) <= 16 || substr($user_email, -10) !== "@gmail.com") {
            $return['error'] = 1;
            $return['msg']   = 'Email không hợp lệ';
            die(json_encode($return));
        }else
        if (strlen($user_username) > 32 || strlen($user_username) < 5) {
            $return['error'] = 1;
            $return['msg']   = 'Tên đăng nhập phải bé hơn 32 kí tự và lớn hơn 5 kí tự.';
            die(json_encode($return));
        } else if (!preg_match("/^[a-zA-Z0-9]*$/", $user_username)) {
            $return['error'] = 1;
            $return['msg']   = 'Tên đăng nhập không bao gồm các kí tự đặc biệt và có dấu.';
            die(json_encode($return));
        }else if (strlen($fullname) < 10) {
            $return['error'] = 1;
            $return['msg']   = 'Vui lòng nhập Họ tên hợp lệ!';
            die(json_encode($return));
        } else if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $return['error'] = 1;
            $return['msg']   = 'Email không đúng định dạng';
            die(json_encode($return));
        } else if ($check_username_available > 0) {
            $return['error'] = 1;
            $return['msg']   = 'Tên đăng nhập đã tồn tại trên hệ thống.';
            die(json_encode($return));
        } else if ($check_email_available > 0) {
            $return['error'] = 1;
            $return['msg']   = 'Địa chỉ email đã tồn tại trên hệ thống.';
            die(json_encode($return));
        } else {
            $stmt = $conn->prepare("INSERT INTO tai_khoan(username, email, mat_khau, trang_thai, quyen, truy_cap, ngay_tao, fullname) VALUES(?, ?, ?, 1,0, ?, ?, ?)");
            $stmt->bind_param("sssiss", $user_username, $user_email, $user_password, $access, $date_create, $fullname);
            if ($stmt->execute()) {
                $return['msg'] = 'Đăng ký tài khoản thành công.';
                die(json_encode($return));
            } else {
                $return['error'] = 1;
                $return['msg'] = 'Đã xảy ra lỗi, vui lòng thử lại sau !.';
                die(json_encode($return));
            }
        }
    }

    if($type == 'recover_pw'){
        $email = $_POST['email'];
        $newpass = $_POST['newpass'];
        if(strlen($newpass)<5){
            $return['error'] = 1;
            $return['msg'] = 'Mật khẩu phải từ 5 kí tự.';
            die(json_encode($return));
        }
        $newpass = md5($newpass);
        $stmt = $conn->prepare("UPDATE tai_khoan SET mat_khau = ? WHERE email = ?");
        $stmt->bind_param("ss", $newpass, $email);
        $stmt->execute();
        $stmt = $conn->prepare("DELETE FROM resettk WHERE email = ?");
        $stmt->bind_param("s",$email);
        if($stmt->execute()){
            $return['msg'] = 'Mật khẩu đã được cập nhật thành công.';
            die(json_encode($return));
        }else{
            $return['error'] = 1;
            $return['msg'] = 'Có lỗi xảy ra khi cập nhật mật khẩu.';
            die(json_encode($return));
        }
    }

    if ($type === 'change_password') {
        $user_password = mysqli_real_escape_string($conn, $_POST['user_password']);
        $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
        $user_new_password = mysqli_real_escape_string($conn, $_POST['user_new_password']);
        $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
        $user_new_password = md5($user_new_password);
        $stmt = $conn->prepare("SELECT mat_khau FROM tai_khoan WHERE email = ?");
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();
        $check_username = $result->fetch_assoc();
        if ($check_username['mat_khau'] != md5($user_password)) {
            $return['error'] = 1;
            $return['msg'] = 'Mật khẩu cũ không chính xác.';
            die(json_encode($return));
        } else {
            $stmt = $conn->prepare("UPDATE tai_khoan SET mat_khau = ?,  fullname = ?  WHERE email = ?");
            $stmt->bind_param("sss", $user_new_password, $fullname, $user_email);
            if ($stmt->execute()) {
                $return['msg'] = 'Đổi thông tin tài khoản thành công, nên đăng nhập lại để làm mới phiên.';
                die(json_encode($return));
            } else {
                $return['error'] = 1;
                $return['msg'] = 'Đã xảy ra lỗi, vui lòng báo lại cho admin';
                die(json_encode($return));
            }
        }
    }

    if($type === 'lock'){
        $id = $_POST['id'];
        $stmt = $conn->prepare("SELECT quyen FROM tai_khoan WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $check = $result->fetch_assoc();
        if($check['quyen'] == 1){
            $return['error'] = 1;
            $return['msg'] = 'Không thể khoá tài khoản có quyền admin.';
            die(json_encode($return));
        }
        $stmt = $conn->prepare("UPDATE tai_khoan SET trang_thai=0 WHERE id = ?");
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            $return['msg'] = 'Đã khoá tài khoản có id '.$id;
            die(json_encode($return));
        }
        else {
            $return['error'] = 1;
            $return['msg'] = 'Đã xảy ra lỗi khi cập nhật, vui lòng thử lại sau.';
            die(json_encode($return));
        }
    }

    if($type === 'unlock'){
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE tai_khoan SET trang_thai=1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            $return['msg'] = 'Đã khoá tài khoản có id '.$id;
            die(json_encode($return));
        }
        else {
            $return['error'] = 1;
            $return['msg'] = 'Đã xảy ra lỗi khi cập nhật, vui lòng thử lại sau.';
            die(json_encode($return));
        }
    }

    if($type === 'forgot'){
        $email = $_POST['email'];
        $sql = 'select * from tai_khoan where email=?';
        $stm = $conn->prepare($sql);
        $stm->bind_param('s', $email);
        $stm->execute();
        $result = $stm->get_result();
        if($result->num_rows == 0){
            $return['error'] = 1;
            $return['msg'] = 'Không tồn tại email';
            die(json_encode($return));
        }else{
            $token = bin2hex(random_bytes(25));
            $exp = date('Y-m-d H:i:s', strtotime('+1 minutes')); 
            $sql2='update resettk set tokenrs=?, exp=? where email=?';
            $stmt = $conn->prepare($sql2);
            $stmt->bind_param('sss', $token, $exp, $email);
            $stmt->execute();
            if($stmt->affected_rows == 0){
                $sql3='insert into resettk (email,tokenrs, exp) values (?,?,?)';
                $stmt = $conn->prepare($sql3);
                $stmt->bind_param('sss', $email, $token, $exp);
                $stmt->execute();
            }
            $result = sendresetpw($email, $token);
            if ($result == true) {
                $return['msg']= "OK";
                die(json_encode($return));
            }
        }
    }

    if($type === 'updatestar'){
        $username = $_POST['username'];
        $curruser = $_POST['curruser'];
        if($username == $curruser){
            $return['error'] = 1;
            $return['msg'] = 'Không thể đánh giá chính mình.';
            die(json_encode($return));
        }
        
        $stmt = $conn->prepare("SELECT FIND_IN_SET(?, nguoidanhgia) FROM tai_khoan WHERE username = ?");
        $stmt->bind_param("ss", $curruser, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if($row['FIND_IN_SET(?, nguoidanhgia)'] != 0){
            $return['error'] = 1;
            $return['msg'] = 'Bạn đã đánh giá rồi.';
            die(json_encode($return));
        }

        $star = $_POST['star'];
        $stmt = $conn->prepare("UPDATE tai_khoan SET star = star+?, nguoidanhgia = CONCAT_WS(',', nguoidanhgia, ?) WHERE username = ?");
        $stmt->bind_param("iss", $star, $curruser, $username);
        if($stmt->execute()){
            $return['msg'] = 'Đã cập nhật đánh giá';
            die(json_encode($return));
        }
        else {
            $return['error'] = 1;
            $return['msg'] = 'Đã xảy ra lỗi khi cập nhật, vui lòng thử lại sau.';
            die(json_encode($return));
        }
    }

    if ($type === 'change_avatar') {
        $username = $_POST['username'];
        if (isset($_FILES['avatar'])) {
            $avatar = $_FILES['avatar'];
            $newFileName = $username . '.png';
            $destination = '../useravt/' . $newFileName;
            if (move_uploaded_file($avatar['tmp_name'], $destination)) {
                $stmt = $conn->prepare("UPDATE tai_khoan SET pathavt = ? WHERE username = ?");
                $stmt->bind_param("ss", $destination, $username);
                $stmt->execute();
                $return['error'] = 0;
                $return['msg'] = 'Thay đổi thành công.Vui lòng đăng nhập lại';
                die(json_encode($return));
            } else {
                $return['error'] = 1;
                $return['msg'] = 'Không thể tải lên hình ảnh.';
                die(json_encode($return));
            }
        }
    }
} 
