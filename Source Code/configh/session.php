<?php
require_once('config.php');
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo "<script type='text/javascript'>document.location.href='login.php';</script>";
    exit;
}

?>