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
    $size = 2048000;


    if (isset($_POST['quiz'])) {
        $question = htmlentities($_POST['question']);
        $question_type = $_POST['question_type'];
        $answer = htmlentities($_POST['answer']);
        $test_id = htmlentities($_POST['exam_no']);
        $options = $_POST['option'];
        $writeOption = $_POST['writeOption'];
        $imagePath = '';

        // Проверяем тип файла
        if ($_FILES['picture']['name'] != null) {
            if (!in_array($_FILES['picture']['type'], $types))
                die('Запрещённый тип файла. <a href="?">Попробовать другой файл?</a>');

            // Проверяем размер файла
            //if ($_FILES['picture']['size'] > $size)
            //    die('Слишком большой размер файла. <a href="?">Попробовать другой файл?</a>');
            // Загрузка файла и вывод сообщения

            @copy($_FILES['picture']['tmp_name'], $path . $_FILES['picture']['name']);
            $imagePath = $path . $_FILES['picture']['name'];
        }

        if (isset($_POST['question_type'])) {
            $addQuestion = addQuestion($test_id, $question, 1, $imagePath);
            $idLastQuestion = getLastIdQuestion();

            if ($answer != null) {
                addAnswer($idLastQuestion, $answer, 1);
                header('location: viewTest.php?test_id=' . $test_id);
            } else {
                $_SESSION['error'][] = "Не заполнен эталонный ответ";
            }

        }
        else {
            $addQuestion = addQuestion($test_id, $question, 0, $imagePath);
            $idLastQuestion = getLastIdQuestion();

            foreach ($options as $id => $text) {
                if ($writeOption[$id] != null) {
                    addAnswer($idLastQuestion, $text, 1);
                } else {
                    addAnswer($idLastQuestion, $text, 0);
                }
            }

            header('location: viewTest.php?test_id=' . $test_id);
        }
    }

?>


