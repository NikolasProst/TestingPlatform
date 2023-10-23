<?php

//login user if there are no errors in the form
function loginUser($email, $pass, $checked) {
    global $conn, $errors;
    //$hash = password_hash($pass, PASSWORD_BCRYPT);
    if (sizeof($errors) == 0) {
        $sql = "SELECT * FROM users WHERE email='".$email."'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            // output data of selected row
            $row = $result->fetch_assoc();
            //if (password_verify($hash, $row['password'])) {
            if ($pass === $row['password']) {
                if (!empty($checked)) {
                    setcookie("useremail", $email, time()+ (10 * 365 * 24 * 60 * 60));
                    setcookie("password", $pass, time()+ (10 * 365 * 24 * 60 * 60));
                }
                $_SESSION['userdata'] = array('user_email'=>$row['email'],'user_id'=>$row['user_id']);
                header('Location: index.php');
            } else {
                $errors[] = array('input'=>'form', 'msg'=>'Invalid password');
            }
        } else {
            $errors[] = array('input'=>'form', 'msg'=>'Wrong Email Address');
            return $errors;
        }

        $conn->close();
    }
    return false;
}

//login admin if there are no errors in the form
function loginAdmin($email, $pass, $checked) {
    global $conn, $errors;
    //$hash = password_hash($pass, PASSWORD_BCRYPT);
    if (sizeof($errors) == 0) {
        $sql = "SELECT * FROM admin WHERE email='".$email."'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            // output data of selected row
            $row = $result->fetch_assoc();
            //if (password_verify($hash, $row['password'])) {
            if ($pass === $row['password']) {
                if (!empty($checked)) {
                    setcookie("admin_email", $email, time()+ (10 * 365 * 24 * 60 * 60));
                    setcookie("admin_password", $pass, time()+ (10 * 365 * 24 * 60 * 60));
                }
                $_SESSION['admindata'] = array('admin_email'=>$row['email'],'admin_id'=>$row['admin_id']);
                header('Location: index.php');
            } else {
                $errors[] = array('input'=>'form', 'msg'=>'Invalid password');
            }
        } else {
            $errors[] = array('input'=>'form', 'msg'=>'Wrong Email Address');
            return $errors;
        }

        $conn->close();
    }
    return false;
}

// check whether user already exist with the same email id
function checkUser($email) {
    global $conn;
    $check_query = "SELECT * FROM users WHERE email='".$email."'";
    $result = $conn->query($check_query);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            if ($row['email'] == $email) {
                $_SESSION['error'][] = $email. " email address already exists";
                break;
            }
        }
        return $_SESSION['error'];
    }
    return false;
}

// register user
function registerUser($name, $email, $password, $gender, $mobile_no, $address, $image) {
    global $conn;
    //$secure_password = password_hash($password, PASSWORD_BCRYPT);
    if (sizeof($_SESSION['error']) == 0) {
        $sql = "INSERT INTO users (name, email, password, gender, mobile_no, address, image) VALUES('".$name."', '".$email."', '".$password."', '".$gender."', '".$mobile_no."', '".$address."', '".$image."')";
        if ($conn->query($sql) === true) {
            $_SESSION['success'] = "Your account has been created! ";
            return $_SESSION['success'];
        } else {
            $_SESSION['error'][] = $conn->error;
            return $_SESSION['error'];
        }
    }
    return false;
}

// add questions to quiz
function addQuestion($testId, $question, $options, $writeOptions, $answer_for_free, $type, $image) {
    global $conn;
    if (empty($_SESSION['error'])) {
        if ($writeOptions != null) {
            $strWriteOptions = implode(',', $writeOptions);
        }

        $sql = "";
        if ($type == 1)
        {
            $sql = "INSERT INTO questions (id_test, text, answer_for_free, type, image)
                            values ('" . $testId . "', '" . $question . "', '" . $answer_for_free . "', '" . $type . "', '" . $image . "')";
        }
        else
        {
            $sql = "INSERT INTO questions (id_test, text, option_1, option_2, option_3, option_4, write_options, type, image)
                            values ('" . $testId . "', '" . $question . "', '" . $options[0] . "', '" . $options[1] . "', '" . $options[2] . "', '" . $options[3] . "', '" . $strWriteOptions . "', '" . $type . "', '" . $image . "')";
        }

        if ($conn->query($sql) === true) {
            $_SESSION['success'] = "Вопрос добавлен в тест ".$testId;
            return $_SESSION['success'];
        } else {
            $_SESSION['error'][] = $conn->error;
            return $_SESSION['error'];
        }
    }
    return false;
}

function getLastIdQuestion() {
    global $conn;
    $sql = 'SELECT MAX(id) id FROM questions';
    $result = $conn ->query($sql) ->fetch_array();
    return $result['id'];
}

function getLastIdTest() {
    global $conn;
    $sql = 'SELECT MAX(id) id FROM tests';
    $result = $conn ->query($sql) ->fetch_array();
    return $result['id'];
}

function getLastIdSubject() {
    global $conn;
    $sql = 'SELECT MAX(id) id FROM subjects';
    $result = $conn ->query($sql) ->fetch_array();
    return $result['id'];
}

function getLastIdCompetence() {
    global $conn;
    $sql = 'SELECT MAX(id) id FROM competences';
    $result = $conn ->query($sql) ->fetch_array();
    return $result['id'];
}

function answer($data)
{
    global $conn;
    $array = array();
    $array['correct_answers'] = array();
    $array['incorrect_answers'] = array();
    $test_id = $data['test_id'];
    $freeAnswer = $data['freeAnswer'];
    $right = 0;
    $wrong = 0;
    $no_answer = 0;
    $sql = "SELECT * FROM questions WHERE id in(" . $data['question_ids'] . ")";
    $result = $conn->query($sql);
    while ($question = $result->fetch_assoc()) {
        $selectAnswerIds = array();
        $userAnswer = isset($freeAnswer[$question['id']]) ? $freeAnswer[$question['id']] : '';

        if ($userAnswer != '') {
            $array['free_answers'][$question['id']] = $userAnswer;
            insertFreeAnswerStats($question, $userAnswer);
        }

        if (isset($data['selectedOptionForQuest'][$question['id']])) {
            $selectAnswerIds = $data['selectedOptionForQuest'][$question['id']];
        }

        $correctAnswers = explode(',', $question['write_options']);

        $has_answered = false;
        if (!empty($selectAnswerIds) || (!empty($userAnswer) && $question['type'] == 1)) {
            $has_answered = true;
        }

        if (!$has_answered) {
            $no_answer++;
        } else {
            $is_right = true;

            if ($question['type'] == 1 && $userAnswer != '') {
                if ($question['answer_for_free'] == $userAnswer) {
                    $right++;
                } else {
                    //TODO: Доработать, когда появится нейросеть
                    //$wrong++;
                }
            } else {
                $options = array();
                $options = [0];
                for ($i = 1; $i <= 4; $i++) {
                    if (!empty($question['option_' . $i])) {
                        $options[] = $question['option_' . $i];
                    }
                }

                foreach ($options as $index => $option) {

                    if ($index != 0) {
                        $is_true = false;
                        if (in_array($index, $correctAnswers)) {
                            $is_true = true;
                        }

                        if ($is_true && !in_array($option, $selectAnswerIds)) {
                            $is_right = false;
                            break;
                        }
                        if (!$is_true && in_array($option, $selectAnswerIds)) {
                            $is_right = false;
                            break;
                        }
                    }
                }

                if ($is_right) {
                    $right++;
                    $array['correct_answers'][$question['id']] = $question['text'];
                } else {
                    $wrong++;
                    $array['incorrect_answers'][$question['id']] = $question['text'];
                }
            }
        }
    }
    $array['test_id'] = $test_id;
    $array['right'] = $right;
    $array['wrong'] = $wrong;
    $array['no_answer'] = $no_answer;

    return $array;
}

function insertFreeAnswerStats($question, $userAnswer)
{
    global $conn;
    $sql = "SELECT id_question, userAnswer FROM freeanswersstats WHERE userAnswer ='" . $userAnswer . "' AND id_question = '" . $question['id'] . "'";
    $result = $conn->query($sql);
    $count = $result->num_rows;

    if ($count == 0) {
        $sql = "INSERT INTO freeanswersstats(ID_QUESTION, TEXT, WRITEANSWER, USERANSWER) 
                    VALUE ('" . $question['id'] . "', '" . $question['text'] . "', '" . $question['answer_for_free'] . "', '" . $userAnswer . "')";
        $conn->query($sql);
    }
}

//shows test's questions in admin panel
function showQuestions()
{
    global $conn, $test_id;

    $sql = "SELECT test_title FROM tests WHERE id='".$test_id."'";
    $result = $conn->query($sql);
    $data = '';
    while ($row = $result->fetch_assoc()) {
        $data = $row['test_title'];
    }

    $sql = "SELECT id, text, option_1, option_2, option_3, option_4, write_options, image, type, answer_for_free, last_update_user, date_update FROM questions WHERE id_test='".$test_id."'";

    $result = $conn->query($sql);
    $count = $result->num_rows;
    echo '<div class="table-title"><h2>'.$data.' (Вопросов '.$count.')</h2><a class="addd" href="addQuestion.php?test_id='. $test_id . '" data-toggle="tooltip" title="Add Question" id="add"><i>Добавить вопрос</i></a>';
    if ($result->num_rows > 0)
    {
        $i = 1;
        while ($rowQuestion = $result->fetch_assoc())
        {
            $html = '<div class="question"> <div><span>Вопрос ' . $i . ':</span>' . $rowQuestion['text'] . '</div>';

            if ($rowQuestion['image'] != null)
            {
                $html .= '<img src="'. $rowQuestion['image'] .'" class="img-thumbnail">';
            }

            if ($rowQuestion['type'] == 1)
            {
                $html .= '<div><span>Эталонный ответ: </span>' . $rowQuestion['answer_for_free'] . '</div>';
            }
            else {
                for ($j = 1; $j <= 4; $j++) {
                    $option_key = 'option_' . $j;
                    if (!empty($rowQuestion[$option_key])) {
                        $html .= '<div><span>Вариант №' . $j . ':</span>' . $rowQuestion[$option_key] . '</div>';
                    }
                }
            }

            if ($rowQuestion['write_options'] != null) {
                $html .= '<div><span>Ответ:</span>' . $rowQuestion['write_options'] . '</div>';
            }
            $html .= '<div><a class="edit" href="editQuestion.php?ques_id=' . $rowQuestion["id"] . '&test_id=' . $test_id . '" data-toggle="tooltip" title="Edit Question">Редактировать вопрос <i class="fa fa-edit"></i></a></div>';

            $html .= '<div><a class="delete" href="deleteTab.php?action=delete&ques_id=' . $rowQuestion["id"] . '&number=' . $i . '&test_id=' . $test_id . '" data-toggle="tooltip" title="Delete Question">Удалить вопрос <i class="fa fa-trash"></i></a></div>';
            if ($rowQuestion['last_update_user'] != null) {
                $html .= '<div style="text-align: right; font-size: 12px;"><span>' . $rowQuestion['last_update_user'] . '</span>редактировал последним</div>';
                $html .= '<div style="text-align: right; font-size: 12px;"><span>' . $rowQuestion['date_update'] . '</span>: дата изменения</div>';
            }
            $html .= '</div> ';
            echo $html;
            $i++;
        }
    }
    return false;
}

function addQuest($testId) {
    header('addQuestion.php?test_id=<?php echo' . $testId . '');
}

//shows test's question in user's dashboard and submit test
function showExamQuestions() {
    global $conn, $test_id, $user_id;
    $sql = "SELECT * FROM questions WHERE id_test='".$test_id."'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $count = $result->num_rows;
        $i = 1;
        echo '<h4>Total '.$count.' questions</h4>';
        $html = '<form action="score.php" method="post" class="exam">';
        while ($row = $result->fetch_assoc()) {
            $html .= '<p>'.$i.') '.$row['text'].'</p>';
            if(isset($row['option1'])) {
                $html .= '<div class="radio"><label><input type="radio" name="'.$row['ques_id'].'" value="0">'.$row['option1'].'</label></div>';
            }
            if(isset($row['option2'])) {
                $html .= '<div class="radio"><label><input type="radio" name="'.$row['ques_id'].'" value="1">'.$row['option2'].'</label></div>';
            }
            if(isset($row['option3'])) {
                $html .= '<div class="radio"><label><input type="radio" name="'.$row['ques_id'].'" value="2">'.$row['option3'].'</label></div>';
            }
            if(isset($row['option4'])) {
                $html .= '<div class="radio"><label><input type="radio" name="'.$row['ques_id'].'" value="3">'.$row['option4'].'</label></div>';
            }
            $html .= '<div class="radio" style="display:none" ><label><input type="radio" checked="checked" name="'.$row['ques_id'].'" value="no_attempt"></label></div>';
            $i++;
        }
        echo $html .= '<input type="hidden" name="exam_no" value="'.$test_id.'" /><input type="hidden" name="user_id" value="'.$user_id.'" /><input type="submit" name="quiz" value="Submit"></form>';
    }
    return false;
}

//user submit test by using previous/next question button
function enableSingleQuestion()
{
    global $conn, $tests_ids, $trainingMode, $countQuestion, $countFreeQuestion;

    $ids = implode(',', $tests_ids);
    $sql = "SELECT * FROM questions WHERE id_test in(" . $ids . ") AND type = 0 ORDER BY RAND() LIMIT " . $countQuestion;
    $sqlFree = "SELECT * FROM questions WHERE id_test in(" . $ids . ") AND type = 1 ORDER BY RAND() LIMIT " . $countFreeQuestion;

    $question_ids = array();
    $resultFree = $conn->query($sqlFree);
    $resultTest = $conn->query($sql);

    $result = array();

    while ($row = $resultFree->fetch_array(MYSQLI_ASSOC))
    {
        $result[] = $row;
    }

    while ($row = $resultTest->fetch_array(MYSQLI_ASSOC))
    {
        $result[] = $row;
    }

    if (count($result) > 0) {
        $count = count($result);
        $i = 1;
        $html = '<form action="score.php" method="post" class="exam" id="examForm" >';
        $html .= '<div class="form-group "> <div class="form-group col-md-auto">';
        foreach ($result as $question)
        {
            $html .= '<div class="tab "><h4>Вопрос ' . $i . ' из ' . $count . ':</h4><p>' . $question['text'] . '</p>';

            $question_ids[] = $question['id'];

            if ($question['image'] != '') {
                $html .= '<img src="admin/' . $question['image'] . '" class="img-thumbnail">';
            }

            $answers = array();
            if ($question['type'] == 1) {
                $answers[] = $question['answer_for_free'];
            } else {
                if (!empty($question['option_1'])) {
                    $answers[] = $question['option_1'];
                }
                if (!empty($question['option_2'])) {
                    $answers[] = $question['option_2'];
                }
                if (!empty($question['option_3'])) {
                    $answers[] = $question['option_3'];
                }
                if (!empty($question['option_4'])) {
                    $answers[] = $question['option_4'];
                }
            }

            $numOfAnswer = count($answers);
            $correctAnswers = explode(',', $question['write_options']);
            $numOfCorrectAnswer = sizeof($correctAnswers);

            if ($numOfAnswer > 0) {
                shuffle($answers); // перемешиваем ответы для каждого вопроса
                foreach ($answers as $answer) {
                    $isCorrect = false;
                    if (strpos($answer, '*') === 0) {
                        $isCorrect = true;
                        $answer = substr($answer, 1); // убираем звездочку из правильного ответа
                    }
                    if ($question['type'] == 1) {
                        if ($trainingMode == 1)
                        {
                            $html .= '<div class="text" style="padding-bottom: 10px"><label><b>Эталонный ответ</b> : ' . $question['answer_for_free'] . '</label><br></div>';
                        }
                        $html .= '<div class="text"><label>Введите ответ </label><br><textarea class="form-control" name="freeAnswer[' . $question['id'] . ']" rows="5"></textarea></div>';
                    } else {
                        if ($numOfCorrectAnswer == 1) {
                            $html .= '<div class="radio"><label><input type="radio" name="selectedOptionForQuest[' . $question['id'] . '][]" value="' . $answer . '"> ' . $answer . '</label></div>';
                        } else {
                            $html .= '<div class="checkbox"><label><input type="checkbox" name="selectedOptionForQuest[' . $question['id'] . '][]" value="' . $answer . '"> ' . $answer . '</label></div>';
                        }
                    }
                }
            }
            $html .= '</div>';
            $i++;
        }
        echo $html .= '<input type="hidden" name="trainingMode" value="' . $trainingMode . '">
        <input type="hidden" name="ids" value="' . $ids . '" />
        <input type="hidden" name="question_ids" value="' . implode(',', $question_ids) . '" />
        <input type="hidden" name="countQuest" value="' . $count . '" />
        <button type="button" id="nextBtn" onclick="nextPrev(1)">Следующий вопрос</button>
        <button type="button" style="float: right; background-color: orangered" id="finishBtn" onclick="finishTest()">Досрочно завершить тест</button></div>
        </form></div>';
    }
    return false;
}

function showSpec()
{
    global $conn;

    $sql = "SELECT * FROM specializations";
    $result = $conn->query($sql);

    while ($row = mysqli_fetch_assoc($result))
    {
        $id = $row['id'];
        $name = $row['name'];
        echo "<option value='$id'>$name</option>";
    }
}

function showSpecWithSelected($selectedSpecId)
{
    global $conn;

    $sql = "SELECT * FROM specializations";
    $result = $conn->query($sql);

    while ($row = mysqli_fetch_assoc($result))
    {
        $id = $row['id'];
        $name = $row['name'];

        if ($selectedSpecId == $id) {
            echo "<option value='$id' selected>$name</option>";
        }
        else {
            echo "<option value='$id'>$name</option>";
        }
    }
}

function showSubject()
{
    global $conn;

    $sql = "SELECT * FROM subjects";
    $result = $conn->query($sql);

    while ($row = mysqli_fetch_assoc($result))
    {
        $id = $row['id'];
        $name = $row['name'];
        echo "<option value='$id'>$name</option>";
    }
}

function showCompetence()
{
    global $conn;

    $sql = "SELECT * FROM competences";
    $result = $conn->query($sql);

    while ($row = mysqli_fetch_assoc($result))
    {
        $id = $row['id'];
        $name = $row['name'];
        echo "<option value='$id'>$name</option>";
    }
}

function showSubjectBy($subjId)
{
    global $conn;
    $sql = "SELECT * FROM subjects";
    $result = $conn->query($sql);

    while ($row = mysqli_fetch_assoc($result))
    {
        $id = $row['id'];
        $name = $row['name'];
        if ($row['id'] == $subjId) {
            echo "<option value='$id' selected>$name</option>";
            continue;
        }
        echo "<option value='$id'>$name</option>";
    }
}

function showCompetenceBy($compId)
{
    global $conn;

    $sql = "SELECT * FROM competences";
    $result = $conn->query($sql);

    while ($row = mysqli_fetch_assoc($result))
    {
        $id = $row['id'];
        $name = $row['name'];
        if ($row['id'] == $compId) {
            echo "<option value='$id' selected >$name</option>";
            continue;
        }
        echo "<option value='$id'>$name</option>";
    }
}

function showSpecBy($specId)
{
    global $conn;

    $sql = "SELECT * FROM specializations";
    $result = $conn->query($sql);

    while ($row = mysqli_fetch_assoc($result))
    {
        $id = $row['id'];
        $name = $row['name'];
        if ($row['id'] == $specId) {
            echo "<option value='$id' selected >$name</option>";
            continue;
        }
        echo "<option value='$id'>$name</option>";
    }
}

?>