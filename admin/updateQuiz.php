<?php
$title = 'Update Question';
include('../config.php');
include('../functions.php');
include('checkAdminData.php');

$path = 'images/';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ques_id = $_POST['ques_id'];
    $test_id = $_POST['test_id'];

    // Получаем данные из формы
    $question = $_POST['question'];
    $writeOptions = isset($_POST['writeOption']) ? $_POST['writeOption'] : array();
    $options = $_POST['option'];
    $answer = isset($_POST['answer']) ? $_POST['answer'] : '';
    $question_type = isset($_POST['question_type']) ? '1' : '0';

    // Обновляем данные о вопросе в таблице questions
    $sqlQues = "UPDATE questions SET text='$question', type='$question_type' WHERE id='$ques_id' AND id_test='$test_id'";
    if ($conn->query($sqlQues) === TRUE) {
        // Обновляем данные об ответах в таблице answers
        for ($i = 0; $i < count($options); $i++) {
            $id_option = $writeOptions[$i];
            $text_option = $options[$i];
            $is_true = (in_array($id_option, $writeOptions)) ? 1 : 0;

            $sqlAns = "UPDATE answers SET text='$text_option', is_true='$is_true' WHERE id='$id_option' AND id_question='$ques_id'";
            $conn->query($sqlAns);
        }

        // Загружаем картинку для вопроса, если была выбрана
        if (!empty($_FILES['picture']['name'])) {
            $image_path = uploadImage($_FILES['picture']['tmp_name'], $path, $test_id);
            $sqlImage = "UPDATE questions SET image='$image_path' WHERE id='$ques_id' AND id_test='$test_id'";
            $conn->query($sqlImage);
        }

        $_SESSION['success'] = "Вопрос успешно изменен.";
    } else {
        $_SESSION['error'] = "Произошла ошибка при изменении вопроса: " . $conn->error;
    }

    header('location: viewTest.php?test_id=' . $test_id);
    exit();
}

// Проверяем, передан ли ID вопроса и теста
if (!isset($_GET['ques_id']) || !isset($_GET['test_id'])) {
    header('Location: test_list.php');
}
$ques_id = $_GET['ques_id'];
$test_id = $_GET['test_id'];

// Получаем данные о вопросе из таблицы questions
$sqlQues = "SELECT * FROM questions WHERE id='$ques_id' AND id_test='$test_id'";
$resultQues = $conn->query($sqlQues);

if ($resultQues->num_rows == 0) {
    header('Location: test_list.php');
}

function uploadImage($file_name, $target_dir, $test_id) {
    $target_file = $target_dir . basename($_FILES[$file_name]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Проверяем, существует ли уже файл с таким именем
    if (file_exists($target_file)) {
        $_SESSION['error'][] = "Файл с таким именем уже существует.";
        header('location: viewTest.php?test_id=' . $test_id);
        die();
    }

    // Проверяем размер файла
    if ($_FILES[$file_name]["size"] > 5000000) {
        $_SESSION['error'][] = "Размер файла слишком большой.";
        header('location: viewTest.php?test_id=' . $test_id);
        die();
    }

    // Разрешаем только определенные типы файлов
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "webp") {
        $_SESSION['error'][] = "Загружены файлы только с расширениями JPG, JPEG, PNG, GIF или WEBP.";
        header('location: viewTest.php?test_id=' . $test_id);
        die();
    }

    // Загружаем файл на сервер
    if (move_uploaded_file($_FILES[$file_name]["tmp_name"], $target_file)) {
        return $target_file;
    } else {
        $_SESSION['error'][] = "Ошибка при загрузке файла.";
        header('location: viewTest.php?test_id=' . $test_id);
        die();
    }
}
?>