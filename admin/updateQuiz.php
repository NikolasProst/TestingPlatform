<?php
$title = 'Update Question';
include('../config.php');
include('../functions.php');
include('checkAdminData.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ques_id = $_POST['ques_id'];
    $test_id = $_POST['test_id'];

    // Получаем данные из формы
    $question = $_POST['question'];
    $writeOptions = isset($_POST['writeOption']) ? $_POST['writeOption'] : array();
    $strWriteOptions = implode(',', $writeOptions);
    $options = $_POST['option'];
    $answer = isset($_POST['answer']) ? $_POST['answer'] : '';
    $question_type = isset($_POST['question_type']) ? '1' : '0';
    $delete_image = isset($_POST['delete_image']) ? '1' : '0';
    $adminData = $_SESSION['admindata']['admin_email'];
    $dateUpdate = date('Y-m-d H:i:s');

    // Обновляем данные о вопросе в таблице questions
    $sqlQues = "UPDATE questions SET text='$question',";
    for ($i = 1; $i <= 4; $i++) {
        if (!empty($options[$i - 1])) {
            $sqlQues .= "option_$i = '" . $options[$i - 1] . "', ";
        }
    }

    $sqlQues .= "write_options='$strWriteOptions', answer_for_free='$answer',";

    if ($delete_image == 1) {
        deleteImageFromServer($ques_id);
        $sqlQues .= "image='', ";
    }

    if (isset($_FILES['picture']) && $_FILES['picture']['name'] != null && $_FILES['picture']['error'] == UPLOAD_ERR_OK) {
        // Проверка на максимальный размер файла
        $max_file_size = 10 * 1024 * 1024; // 10 MB
        if ($_FILES['picture']['size'] > $max_file_size) {
            $_SESSION['error'] ='Файл слишком большой. Максимальный размер файла: ' . $max_file_size / (1024 * 1024) . ' MB';
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;
        }
        $imagePath = saveImage($ques_id);
        $sqlQues .= "image='" . $imagePath . "', ";
    }

    $sqlQues .= "last_update_user='$adminData', date_update='$dateUpdate' WHERE id='$ques_id' AND id_test='$test_id'";

    if ($conn->query($sqlQues) === TRUE) {
        $_SESSION['success'] = "Вопрос успешно изменен.";
        header('location: viewTest.php?test_id=' . $test_id);
    } else {
        $_SESSION['error'] = "Произошла ошибка при изменении вопроса: " . $conn->error;
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }
}

function saveImage($question_id)
{
    global $conn;
    if (!isset($_FILES['picture']) || $_FILES['picture']['error'] == UPLOAD_ERR_NO_FILE) {
        return '';
    }

// Пути загрузки файлов
    $path = 'images/';
    $tmp_path = 'tmp/';
// Массив допустимых значений типа файла
    $types = array('image/png', 'image/jpeg', 'image/jpg');
    $imagePath = '';

    if ($_FILES['picture']['error'] != UPLOAD_ERR_OK) {
        $_SESSION['error'] ='Ошибка загрузки файла: ' . $_FILES['picture']['error'];
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }

    if (!in_array($_FILES['picture']['type'], $types)) {
        $_SESSION['error'] ='Запрещённый тип файла. Допустимые типы файлов: ' . implode(", ", $types);
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }

// Генерация уникального имени файла
    $file_ext = strtolower(pathinfo($_FILES["picture"]["name"], PATHINFO_EXTENSION));
    $file_name = uniqid() . "_" . time() . "." . $file_ext;

    if (!move_uploaded_file($_FILES['picture']['tmp_name'], $path . $file_name)) {
        $_SESSION['error'] ='Ошибка сохранения файла.';
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }

    $imagePath = $path . $file_name;

    deleteImageFromServer($question_id);

    return $imagePath;
}

function deleteImageFromServer($question_id)
{
    global $conn;
    $sql = "SELECT image FROM questions WHERE id=" . $question_id;
    $result = $conn -> query($sql);
    $imagePath = '';
    while ($row = $result->fetch_assoc()) {
        $imagePath = $row['image'];
    }
    if ($imagePath != '') {
        unlink($imagePath);
    }
}