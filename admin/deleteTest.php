<?php

session_start();
include('../config.php');

//remove test from database
if(isset($_GET['action']) && $_GET['action'] == "remove") {
    $test_id = $_REQUEST['test_id'];
    $delete = "DELETE FROM tests WHERE id = '".$test_id."'";
    if ($conn->query($delete) === TRUE) {
        $_SESSION['success'] = "Test ".$test_id." is deleted";
    } else {
        $_SESSION['error'] = $conn->error;
    }
    header('location: tests.php');
}

//remove question from a test
if(isset($_GET['action']) && $_GET['action'] == "delete") {
    $ques_id = $_REQUEST['ques_id'];
    $number = $_REQUEST['number'];
    $test_id = $_REQUEST['test_id'];

    $sql = "SELECT image FROM questions WHERE id='".$ques_id."'";
    $result = $conn->query($sql);
    $data = '';
    while ($row = $result->fetch_assoc()) {
        $data = $row['image'];
    }

    $delete = "DELETE FROM questions WHERE id='".$ques_id."'";
    if ($conn->query($delete) === TRUE) {
        if ($data != '')
            unlink($data);

        $_SESSION['success'] = "Question ".$number." is deleted from Test ".$test_id;
    } else {
        $_SESSION['error'] = $conn->error;
    }



    header('location: viewTest.php?test_id='.$test_id);
}

?>
