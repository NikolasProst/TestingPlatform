<?php
$title = 'Update Question';
include('../config.php');
include('../functions.php');
include('checkAdminData.php');
include('header.php');
if (!isset($_GET['ques_id'])) {
    header('Location: test_list.php');
}
$ques_id = $_GET['ques_id'];
$test_id = $_GET['test_id'];

$sqlQues = "SELECT * FROM questions WHERE id=" . $ques_id . " AND id_test=" . $test_id;
$resultQues = $conn->query($sqlQues);

if ($resultQues->num_rows == 0) {
    header('Location: test_list.php');
}

$rowQues = $resultQues->fetch_assoc();

$correctAnswers = explode(',', $rowQues['write_options']);
$options_array = array(
    $rowQues['option_1'],
    $rowQues['option_2'],
    $rowQues['option_3'],
    $rowQues['option_4']
);
$options_array = array_filter($options_array);

?>
<?php include('sidebar.php'); ?>

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="index.php"><em class="fa fa-home"></em></a></li>
            <li><a href="test_list.php">Тесты</a></li>
            <li><a href="viewTest.php?test_id=<?php echo $test_id ?>">Тест <?php echo $test_id ?></a></li>
            <li>Редактировать вопрос</li>
        </ol>
    </div><!--/.row-->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Редактировать вопрос</h1>
        </div>
    </div><!--/.row-->
    <div class="content-box"><!-- Start Content Box -->
        <div class="content-box-content">
            <?php if (isset($_SESSION['error'])) : ?>
                <span id="message">
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>
                </span>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])) : ?>
                <span id="message">
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                    </div>
                </span>
            <?php endif; ?>
            <form id="addForm" action="updateQuiz.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="form-group col-md-12">
                    <div class="row">
                        <div class="form-group col-md-9">
                            <label for="question">Вопрос</label>
                            <input type="text" class="form-control" name="question" id="question" placeholder="Введите вопрос" value="<?php echo $rowQues['text']; ?>" required/>
                        </div>

                        <?php if ($rowQues['type'] == '1') :?>
                            <div class="form-group col-md-3">
                                <div class="form-check" style="margin-top: 45px;">
                                    <input type="checkbox" class="form-check-input" value="Yes" name="question_type"
                                           id="question_type" <?php if ($rowQues['type'] == '1') : ?> checked disabled <?php endif; ?>>
                                    <label class="form-check-label" for="exampleCheck1">Свободный ответ</label>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($rowQues['type'] != '1') :?>
                            <div class="form-group col-md-3">
                                <div class="form-check" style="margin-top: 45px;">
                                    <input type="checkbox" class="form-check-input" value="Yes" name="question_type"
                                           id="question_type" disabled>
                                    <label class="form-check-label" for="exampleCheck1">Свободный ответ</label>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($rowQues['type'] != '1') : ?>
                        <div id="optionDiv">
                            <?php
                            $i = 1;
                            foreach ($options_array as $option)
                            {
                                $isChecked = in_array($i, $correctAnswers) ? 'checked' : '';
                                echo '<div class="form-group" id="div_option_' . $i . '">
                                        <input type="checkbox" class="form-check-input" name="writeOption[]" value="' . $i . '" ' . $isChecked . '>
                                        <label for="option_1" id="label_option1">Вариант ' . $i . '</label>
                                        <input type="text" class="form-control" name="option[]" value="' . $option . '"/>
                                      </div>';

                                $i++;
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($rowQues['type'] == '1') :?>
                        <div class="form-group">
                            <label for="question">"Эталонный ответ"</label>
                            <input type="text" class="form-control" name="answer" placeholder="Введите эталонный ответ"
                                   value="<?php echo $rowQues['answer_for_free']; ?>"/>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <?php if ($rowQues['image'] != '') : ?>
                            <div class="question-image">
                                <img src="<?php echo $rowQues['image']; ?>" alt="Question Image" class="img-thumbnail"/>
                                <input type="checkbox" name="delete_image" value="Yes" style="margin-left: 10px;">Удалить картинку
                            </div>
                        <?php endif; ?>
                            <label for="question">Загрузить картинку для вопроса</label>
                            <input type="file" class="form-control" name="picture"/>
                    </div>
                </div>
                <?php if ($rowQues['type'] != '1') :?>
                    <button type="button" class="btn btn-default" name="addOptionButton" onclick="addOption(<?php echo $options_array->num_rows; ?>)">Добавить вариант ответа</button>
                    <button type="button" class="btn btn-default" name="delOptionButton" onclick="delOption()">Удалить вариант ответа</button>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary">Сохранить изменения</button>

                <input type="hidden" name="ques_id" value="<?php echo $ques_id; ?>"/>
                <input type="hidden" name="test_id" value="<?php echo $test_id; ?>"/>

            </form>
        </div><!-- End .content-box-content -->
    </div><!-- End .content-box -->
    <script type="text/javascript">
        //Добавить вариант ответа
        function addOption() {
            var maxOptions = 4;
            var optionDiv = document.getElementById('optionDiv');

            if (optionDiv != null && optionId < maxOptions) {
                var newOption = document.createElement('div');
                newOption.className = 'form-group';
                newOption.id = 'div_option_' + optionId;
                newOption.innerHTML = '<input type="checkbox" class="form-check-input" name="writeOption[]" value="' + optionId + '"/>' +
                    '<label for="option_' + optionId + '">Вариант №' + optionId + '</label>' +
                    ' <input type="text" class="form-control" name="option[]" required"/>';

                optionDiv.appendChild(newOption);
                optionId++;
            } else {
                alert('Достигнуто максимальное кол-во вариантов ответа!');
            }
        }

        //Удалить вариант ответа
        function delOption() {
            var optionDiv = document.getElementById('optionDiv');
            var option = document.getElementById('div_option_' + optionId + '');

            if (optionDiv != null && optionId > 2) {
                optionDiv.removeChild(option);
                optionId--;
            } else {
                alert('Достигнуто минимальное кол-во вариантов ответа!');
            }
        }

        function validateForm() {
            var questionType = document.getElementById('question_type').checked;
            var options = document.getElementsByName('writeOption[]');
            var i, isSelected = false;

            if (questionType) {
                var answer = document.getElementsByName('answer')[0].value.trim();
                if (!answer) {
                    alert('Введите "эталонный ответ".');
                    return false;
                }
            } else {
                for (i = 0; i < options.length; i++) {
                    if (options[i].checked) {
                        isSelected = true;
                        break;
                    }
                }
                if (!isSelected) {
                    alert('Выберите хотя бы один вариант ответа.');
                    return false;
                }
            }
            return true;
        }
    </script>

</div> <!--/.main-->

<?php
include('footer.php');
?>
