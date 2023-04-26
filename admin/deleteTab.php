<?php

session_start();
include('../config.php');

//remove test from database
if(isset($_GET['action']) && $_GET['action'] == "removeTest") {
    $test_id = $_REQUEST['test_id'];
    $delete = "DELETE FROM tests WHERE id = '".$test_id."'";
    if ($conn->query($delete) === TRUE) {
        $_SESSION['success'] = "Тест удален";
    } else {
        $_SESSION['error'] = $conn->error;
    }
    header('location: test_list.php');
}

if(isset($_GET['action']) && $_GET['action'] == "removeSpec") {
        $spec_id = $_REQUEST['spec_id'];
        $delete = "DELETE FROM specializations WHERE id = '" . $spec_id . "'";
        if ($conn->query($delete) === TRUE) {
            $_SESSION['success'] = "Направление удалено";
        } else {
            $_SESSION['error'] = $conn->error;
        }
        header('location: spec_list.php');
}

if(isset($_GET['action']) && $_GET['action'] == "removeSubj") {
        $subj_id = $_REQUEST['subj_id'];
        $delete = "DELETE FROM subjects WHERE id = '" . $subj_id . "'";
        if ($conn->query($delete) === TRUE) {
            $_SESSION['success'] = "Предмет удален";
        } else {
            $_SESSION['error'] = $conn->error;
        }
        header('location: subj_list.php');
}

if(isset($_GET['action']) && $_GET['action'] == "removeComp") {
        $comp_id = $_REQUEST['comp_id'];
        $delete = "DELETE FROM competences WHERE id = '" . $comp_id . "'";
        if ($conn->query($delete) === TRUE) {
            $_SESSION['success'] = "Компетенция удалена";
        } else {
            $_SESSION['error'] = $conn->error;
        }
        header('location: comp_list.php');
}

if(isset($_GET['action']) && $_GET['action'] == "removeConStc") {
        $comp_id = $_REQUEST['comp_id'];
        $subj_id = $_REQUEST['subj_id'];
        $delete = "DELETE FROM subjects_to_competence WHERE id_subject = '" . $subj_id . "' and id_competence = '".$comp_id."'";
        if ($conn->query($delete) === TRUE) {
            $_SESSION['success'] = "Связь удалена";
        } else {
            $_SESSION['error'] = $conn->error;
        }
        header('location: connectSubjToComp_list.php');
}

if(isset($_GET['action']) && $_GET['action'] == "removeConSts") {
        $spec_id = $_REQUEST['spec_id'];
        $subj_id = $_REQUEST['subj_id'];
        $delete = "DELETE FROM specialization_to_subjects WHERE id_subject = '" . $subj_id . "' and id_specialization = '".$spec_id."'";
        if ($conn->query($delete) === TRUE) {
            $_SESSION['success'] = "Связь удалена";
        } else {
            $_SESSION['error'] = $conn->error;
        }
        header('location: connectSpecToSubj_list.php');
}

if(isset($_GET['action']) && $_GET['action'] == "delete")
{
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

            $_SESSION['success'] = "Вопрос ".$number." удален из теста ";
        } else {
            $_SESSION['error'] = $conn->error;
        }
        header('location: viewTest.php?test_id='.$test_id);
}

?>
