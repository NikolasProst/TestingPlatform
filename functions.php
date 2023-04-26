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
function addQuestion($testId, $question, $type, $image) {
    global $conn;
    if (empty($_SESSION['error'])) {
        $sql = "INSERT INTO questions (id_test, text, type, image) values ('".$testId."', '".$question."', '".$type."', '".$image."')";
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

function addAnswer($idQuestion, $text, $isTrue) {
    global $conn;
    if (empty($_SESSION['error'])) {
        $sql = "INSERT INTO answers (id_question, text, is_true) values ('".$idQuestion."', '".$text."', '".$isTrue."')";
        if ($conn->query($sql) === true) {
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

//check exam's result and shows it to user in their dashboard
function answer($data) {
    global $conn;
    $test_id = $_POST['test_id'];
    $right = 0;
    $wrong = 0;
    $no_answer = 0;

    $sql = "SELECT * FROM questions WHERE id_test='".$test_id."'";
    $result = $conn->query($sql);
    while($question = $result->fetch_assoc())
    {
        $sql = "SELECT * FROM answers WHERE id_question = '" . $question['id'] . "'";
        $answers = $conn->query($sql);

        $selectAnswerId = $_POST[$question['id']];

        while($answer = $answers->fetch_assoc())
        {
            if ($selectAnswerId == $answer['id'])
            {
                if ($answer['is_true'] == '1') {
                    $right++;
                    break 1;
                } else {
                    $wrong++;
                    break 1;
                }
            }

            if ($selectAnswerId == "no_attempt") {
                $no_answer++;
                break;
            }
        }
    }

    $array = array();
    $array['test_id'] = $test_id;
    $array['right'] = $right;
    $array['wrong'] = $wrong;
    $array['no_answer'] = $no_answer;

    //$marks = implode(',', $array);

    //$sql1 = "INSERT INTO user_exam_result (user_id, exam_id, marks, date) VALUES('".$user_id."', '".$test_id."', '".$marks."', now())";
    //$conn->query($sql1);
    return $array;
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

    $sql = "SELECT id, text, image FROM questions WHERE id_test='".$test_id."'";

    $result = $conn->query($sql);
    $count = $result->num_rows;
    echo '<div class="table-title"><h2>'.$data.' (Вопросов '.$count.')</h2><a class="addd" href="addQuestion.php?test_id='. $test_id . '" data-toggle="tooltip" title="Add Question" id="add"><i>Добавить вопрос</i></a>';
    if ($result->num_rows > 0)
    {
        $i = 1;
        while ($rowQuestion = $result->fetch_assoc())
        {
            $sql1 = "SELECT id_question, text, is_true FROM answers where id_question = '".$rowQuestion['id']."'";
            $answers = $conn->query($sql1);

            $j = 1;
            $html = '<div class="question"> <div><span>Вопрос ' . $i . ':</span>' . $rowQuestion['text'] . '</div>';

            if ($rowQuestion['image'] != '')
            {
                $html .= '<img src="'. $rowQuestion['image'] .'" class="img-thumbnail">';
            }

            $trueAnswers = "";

            while ($rowAnswer = $answers->fetch_assoc())
            {
                $html .= '<div><span>Вариант №' . $j . ':</span>' . $rowAnswer['text'] . '</div>';

                if ($rowAnswer['is_true'] == 1)
                    $trueAnswers .= $j. ' ';

                $j++;
            }

            $html .= '<div><span>Ответ:</span>'. implode(',', explode(' ', trim($trueAnswers))) .'</div> 
                <div><a class="delete" href="deleteTab.php?action=delete&ques_id='.$rowQuestion["id"].'&number='.$i.'&test_id='.$test_id.'" data-toggle="tooltip" title="Delete Question">Удалить вопрос <i class="fa fa-trash"></i></a></div> 
                </div> ';
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

function showTestTable() {

}

//user submit test by using previous/next question button
function enableSingleQuestion() {
    global $conn, $test_id, $user_id;;
    $sql = "SELECT * FROM questions WHERE id_test='".$test_id."' ORDER BY RAND()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $count = $result->num_rows;
        $i = 1;
        $html = '<form action="score.php" method="post" class="exam" id="examForm" >';
        $html .= '<div class="form-group "> <div class="form-group col-md-auto">';
        while ($question = $result->fetch_assoc()) {

            $html .= '<div class="tab "><h4>Вопрос '.$i.' of '.$count.':</h4><p>'.$question['text'].'</p>';

            if ($question['image'] != '')
            {
                $html .= '<img src="admin/'. $question['image'] .'" class="img-thumbnail">';
            }

            $sql = "SELECT * FROM answers WHERE id_question = '" . $question['id'] . "' ORDER BY RAND()";
            $answers = $conn->query($sql);


            if ($answers->num_rows > 0)
            {
                $numOfAnswer = 1;
                while ($answer = $answers->fetch_assoc())
                {
                    $html .= '<div class="radio"><label><input type="checkbox" name="writeOptionForQuest[' . $question['id'] . ']" value="'.$answer['id'].'"> '.$answer['text'].'</label></div>';

                    $numOfAnswer++;
                }
            }


            //$html .= '<div class="radio" style="display:none" ><label><input type="checkbox" checked="checked" name="'.$row['id'].'" value="no_attempt"></label></div>';
            $html .= '</div>';
            $i++;
        }


        echo $html .= '<input type="hidden" name="test_id" value="'.$test_id.'" /><button type="button" id="nextBtn" onclick="nextPrev(1)">Следующий вопрос</button></div></form></div>';
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

?>