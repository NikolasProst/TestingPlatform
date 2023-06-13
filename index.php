<?php
$title = "Tests List";

include('config.php');
include('functions.php');
//check session variable is set
//if (!isset($_SESSION['userdata']['user_email'])) {
//    header('location: index.php');
//}
//$user_id = $_SESSION['userdata']['user_id'];

if (isset($_POST['startTest'])) {
    $specialization = $_POST['specialization'];
    $subject = $_POST['subject'];
    $competence = $_POST['competence'];
    $trainingMode = $_POST['isTrainingMode'];
    $countQuestion = $_POST['countQuestion'];
    $countFreeQuestion = $_POST['countFreeQuestion'];

    $sql = '';
    if ($specialization != 0) {
        if ($subject != 0 && $competence != 0) {
            $sql = "SELECT id FROM tests where id_specialization = '" . $specialization . "' and id_subject = '" . $subject . "' and id_competence = '" . $competence . "' ORDER BY RAND() limit 1";
        } else {
            $sql = "";
            if ($subject != 0 && $competence == 0) {
                $sql .= "SELECT id FROM tests where id_specialization = '" . $specialization . "' and id_subject = '" . $subject . "' ORDER BY RAND()";
            }
            if ($competence != 0 && $subject == 0) {
                $sql .= "SELECT id FROM tests where id_specialization = '" . $specialization . "' and id_competence = '" . $competence . "' ORDER BY RAND()";
            }
        }
    }

    if ($sql != '') {
        $result = $conn->query($sql);
    }

    $tests = array();

    $getPath = '';
    if (!empty($result)) {
        while ($test = $result->fetch_assoc()) {
            $tests[] = $test['id'];
        }
    }

    foreach ($tests as $test) {
        $getPath .= 'tests_ids[]=' . urlencode($test) . '&';
    }
    $getPath = rtrim($getPath, '&');

    if (!empty($tests)) {
        header('location: exam.php?competence='. $competence .'&subject=' . $subject .'&trainingMode=' . $trainingMode . '&countQuestion=' . $countQuestion . '&countFreeQuestion=' . $countFreeQuestion . '&'. $getPath);
    }
}

include('header.php');
?>
    <div class="container content">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h3 class="center">Добро пожаловать на платформу тестирования</h3>
                <br>
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
                        <form action="" method="post">
                            <!-- This is the target div. id must match the href of this div's tab -->
                            <div class="form-group ">
                                <div class="form-group col-md-auto">
                                    <div class="form-group">
                                        <label for="inputState">Направление</label>
                                        <select id="specialization" class="form-control" name="specialization"
                                                id="specialization" onchange="getSubjectsAndCompetences(this.value)"
                                                required>
                                            <option value="0">Выберите направление</option>
                                            <?php showSpec() ?>
                                        </select>
                                    </div>

                                    <div id="subjectAndCompetence">
                                        <div class="form-group">
                                            <label for="inputState">Предмет</label>
                                            <select id="subject" class="form-control" name="subject" id="subject"
                                                    onchange="filterCompetence(this.value)">
                                                <option value="0">Выберите предмет</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputState">Компетенция</label>
                                            <select id="competence" class="form-control" name="competence"
                                                    onchange="filterSubject(this.value)" required>
                                                <option value="0">Выберите компетенцию</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="isTrainingMode" id="isTrainingMode" value="0">
                                    <div class="form-group col-md-auto">
                                        <div class="form-check" style="float: right">
                                            <input class="form-check-input" type="checkbox" id="trainingModeCheckbox" name="trainingMode">
                                            <label class="form-check-label" for="trainingModeCheckbox">Режим тренировки</label>
                                        </div>

                                        <div class="form-group">
                                            <label class="inputState" for="countQuestion">Количество вопросов в тесте</label>
                                            <input class="form-input" type="number" style="width: 50px" max="100" value="15" min="0" id="countQuestion" name="countQuestion" >
                                        </div>

                                        <div class="form-group">
                                            <label class="inputState" for="countFreeQuestion">Количество вопросов с свободным ответом</label>
                                            <input class="form-input" type="number" style="width: 50px" max="100" value="15" min="0" id="countFreeQuestion" name="countFreeQuestion">
                                        </div>

                                        <button type="submit" class="btn btn-primary" name="startTest">Сформировать тест</button>
                                    </div>
                                </div>
                        </form>
                    </div> <!-- End .content-box-content -->
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('trainingModeCheckbox').addEventListener('change', function() {
            var hiddenInput = document.getElementsByName('isTrainingMode')[0];
            hiddenInput.value = this.checked ? 1 : 0;
        });
    </script>
<?php include('footer.php');

