<?php
    session_start();
    //check session variable is set
    if (!isset($_SESSION['admindata']['admin_email'])) {
        header('location: login.php');
    }
?>
