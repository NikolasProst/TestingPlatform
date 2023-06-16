<?php
session_start();
include_once('../config.php');
include_once('../functions.php');
include('checkAdminData.php');

// Пути загрузки файлов
$path = 'images/';
$tmp_path = 'tmp/';
// Массив допустимых значений типа файла
$types = array('image/png', 'image/jpeg', 'image/jpg');
// Максимальный размер файла
$max_file_size = 10 * 1024 * 1024; // 10 MB

if (isset($_POST['quiz'])) {
    $question = htmlentities($_POST['question']);
    $question_type = $_POST['question_type'];
    $answer = htmlentities($_POST['answer']);
    $test_id = htmlentities($_POST['exam_no']);
    $options = $_POST['option'];
    $writeOption = $_POST['writeOption'];
    $imagePath = '';

    // Проверяем, был ли выбран файл
    if (!empty($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        // Проверяем тип файла
        if (!in_array($_FILES['picture']['type'], $types)) {
            $_SESSION['error'] ='Запрещённый тип файла. Допустимые типы файлов: ' . implode(", ", $types);
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;
        }

        // Проверяем размер файла
        if ($_FILES['picture']['size'] > $max_file_size) {
            $_SESSION['error'] ='Файл слишком большой. Максимальный размер файла: ' . $max_file_size / (1024 * 1024) . ' MB';
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;
        }

        // Генерируем уникальное имя для файла
        $filename = uniqid() . '.' . pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);

        // Загрузка файла и вывод сообщения
        if (@move_uploaded_file($_FILES['picture']['tmp_name'], $path . $filename)) {
            $imagePath = $path . $filename;
        } else {
            $_SESSION['error'] ='Ошибка сохранения файла.';
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;
        }
    }

    // Свободный вопрос
    if (isset($_POST['question_type'])) {
        $addQuestion = addQuestion($test_id, $question, null, null, $answer,  1, $imagePath);
        $_SESSION['success'] = "Вопрос успешно создан.";
        header('location: viewTest.php?test_id=' . $test_id);
    } else {
        $addQuestion = addQuestion($test_id, $question, $options, $writeOption, null, 0, $imagePath);
        $_SESSION['success'] = "Вопрос успешно создан.";
        header('location: viewTest.php?test_id=' . $test_id);
    }
}

?>


