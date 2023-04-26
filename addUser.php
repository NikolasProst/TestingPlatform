<?php

session_start();
include_once('config.php');
include_once('functions.php');
if (isset($_POST['register'])) {
    $name = isset($_POST['user_name'])?$_POST['user_name']:'';
    $email = isset($_POST['user_email'])?$_POST['user_email']:'';
    $password = isset($_POST['password'])?$_POST['password']:'';
    $confirm_password = isset($_POST['confirm_password'])?$_POST['confirm_password']:'';
    if ($password != $confirm_password) {
        $_SESSION['error'][] = 'Passwords do not match';
    }
    $gender = $_POST['gender'];
    $mobile_no = isset($_POST['mobile_no'])?$_POST['mobile_no']:'';
    $address = isset($_POST['address'])?$_POST['address']:'';
    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $location = "images/";
    move_uploaded_file($tmp_name,$location.$image);
    //check user already exists
    $checkUser = checkUser($email);

    //register user
    $registerUser = registerUser($name, $email, $password, $gender, $mobile_no, $address, $image);
    header('location: register.php');

} 

?>


