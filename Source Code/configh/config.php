<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'data');
date_default_timezone_set('Asia/Ho_Chi_Minh');
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
function checkvar($email, $token){
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $sql = "SELECT * FROM resettk WHERE email = ? AND tokenrs = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $exp = strtotime($row['exp']);
        $now = time();
        if ($exp < $now) {
            return array('code'=>'2', 'error'=>'Token hết hạn');
        } else {
            return array('code'=>'0', 'message'=>'Success');
        }
    } else {
        return array('code'=>'1', 'error'=>'Email hoặc token không hợp lệ');
    }
}
?>