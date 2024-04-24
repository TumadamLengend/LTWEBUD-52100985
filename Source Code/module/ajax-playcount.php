<?php
require_once("../configh/config.php");
if ($_REQUEST) {
    $return = array(
        'error' => 0
    );
    $type = $_REQUEST['type'];
    if ($type === 'updatecount') {
        $songId = $_POST['id'];
        $stmt = $conn->prepare("UPDATE songs SET play_count = play_count + 1 WHERE id = ?");
        $stmt->bind_param("i", $songId);
        if ($stmt->execute()) {
            $return['msg'] = 'Play count increased successfully.';
        } else {
            $return['error'] = 1;
            $return['msg'] = 'Database error: ' . $stmt->error;
        }
        die(json_encode($return));
    }

} 
