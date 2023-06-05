<?php

session_start();
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['is_true'])) {
    // Преобразуем массив is_true в строку, разделенную запятыми, для хранения в базе данных
    $is_true = implode(',', $_POST['is_true']);
    $sql = "UPDATE freeanswersstats SET is_true = CASE id ";
    foreach ($_POST['is_true'] as $id) {
        $sql .= "WHEN $id THEN 1 ";
    }
    $sql .= "ELSE 0 END";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Ответы успешно сохранены';
    } else {
        $_SESSION['error'] = 'Ошибка при сохранении ответов: ' . $conn->error;
    }
}

header('Location: freeAnswers_list.php');
exit();

?>