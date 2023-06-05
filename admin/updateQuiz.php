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

    print_r($_POST);

    // Обновляем данные о вопросе в таблице questions
    $sqlQues = "UPDATE questions SET text='$question',";
    for ($i = 1; $i <= 4; $i++) {
        if (!empty($options[$i - 1])) {
            $sqlQues .= "option_$i = '" . $options[$i - 1] . "', ";
        }
    }

    $sqlQues .= "write_options='$strWriteOptions', answer_for_free='$answer',";

    if ($delete_image == 1 && $_FILES['picture']['name'] == null)
    {
        deleteImage($ques_id);
        $sqlQues .= "image='', ";
    }
    else {
        $imagePath = updateImage($ques_id);
        $sqlQues .= "image='$imagePath', ";
    }

    $sqlQues .= "last_update_user='$adminData', date_update='$dateUpdate' WHERE id='$ques_id' AND id_test='$test_id'";

    print_r($sqlQues);

    if ($conn->query($sqlQues) === TRUE) {
        $_SESSION['success'] = "Вопрос успешно изменен.";
        header('location: viewTest.php?test_id=' . $test_id);
    } else {
        $_SESSION['error'] = "Произошла ошибка при изменении вопроса: " . $conn->error;
    }

}

function updateImage($ques_id)
{
    global $conn;
    $sql = "SELECT image FROM questions WHERE id='".$ques_id."'";
    $result = $conn->query($sql);
    $data = '';

    while ($row = $result->fetch_assoc()) {
        $data = $row['image'];
    }

    // Пути загрузки файлов
    $path = 'images/';
    $tmp_path = 'tmp/';
    // Массив допустимых значений типа файла
    $types = array('image/png', 'image/jpeg', 'image/jpg');
    $imagePath = '';

    if ($_FILES['picture']['name'] != null) {
        if (!in_array($_FILES['picture']['type'], $types))
            die('Запрещённый тип файла. <a href="?">Попробовать другой файл?</a>');

        @copy($_FILES['picture']['tmp_name'], $path . $_FILES['picture']['name']);
        $imagePath = $path . $_FILES['picture']['name'];

        if ($data != '')
            unlink($data);
    }
    return $imagePath;
}

function deleteImage($ques_id)
{
    global $conn;
    $sql = "SELECT image FROM questions WHERE id='".$ques_id."'";
    $result = $conn->query($sql);
    $imagePath = '';

    while ($row = $result->fetch_assoc()) {
        $imagePath = $row['image'];
    }
    if ($imagePath != '') {
        unlink($imagePath);
    }
}
?>