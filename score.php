<?php 
$title = "Score";
include('header.php');
include_once('config.php');
include_once('functions.php');
//check session variable is set

//calls function for checking answers & return array 
$answer = answer($_POST);
?>
        <div class="container content score">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                	<?php
                    $total_question = $_POST['countQuest'];
                	$attempt_question = $answer['right']+$answer['wrong'];
                	?>
                	<h1>
                        Результат:
                	</h1>
                	<table class="table table-bordered">
					    <thead>
					    	<tr>
					    		<th>Количество вопросов в тесте</th>
					            <th><?php echo $total_question; ?></th>
					      </tr>
					    </thead>
					    <tbody>
					      	<tr>
					        	<td>Правильные ответы</td>
					        	<td><?php echo $answer['right']; ?></td>
					      	</tr>
					      	<tr>
					        	<td>Неправильные ответы</td>
					        	<td><?php echo $answer['wrong']; ?></td>
					      	</tr>
					      	<tr>
					        	<td>Вопросов пропущено</td>
					        	<td><?php echo $answer['no_answer']; ?></td>
					      	</tr>
					      	<tr class="result">
					        	<td>Ваш результат</td>
					        	<td><?php
					        	$percentage = ($answer['right']/$total_question)*100;
					        	echo $percentage."%";
					        	?></td>
					      	</tr>
					    </tbody>
  					</table>
  					<br/>
                    <?php
                    if($percentage < 50) {
                        echo '<h4 class="center">😓 Вы должны набрать больше 50% правильных ответов, чтобы пройти тест.</h4>';
                    }
                    else {
                        echo '<h4 class="center">🙂🥳 Вы прошли тест, Ваш результат '.round($percentage).'%.</h4>';
                    }

                    ?>
                    <br/>
                    <?php
                    if (!empty($answer['incorrect_answers'])) {
                        echo "<h3>Неправильные ответы:</h3>";
                        $html = '<div class="content-box"><div class="content-box-content">';
                        foreach ($answer['incorrect_answers'] as $q_id => $q_text) {
                            $html .= '<div class="question"> <div><span>Вопрос: </span>' . $q_text . '</div>';
                            $sql = "SELECT id, text, option_1, option_2, option_3, option_4, write_options, image, type, answer_for_free FROM questions WHERE id='" . $q_id . "'";
                            $result = $conn->query($sql);
                            $question = $result->fetch_assoc();
                            if ($question['image'] != null) {
                                $html .= '<img src="admin/' . $question['image'] . '" class="img-answer">';
                            }
                            $selectAnswerIds = $_POST['selectedOptionForQuest'][$question['id']];
                            $writeOptions = explode(',', $question['write_options']);
                            for ($j = 1; $j <= 4; $j++) {
                                $option_key = 'option_' . $j;
                                if (!empty($question[$option_key])) {
                                    $color = "white";
                                    if (in_array($question[$option_key], $selectAnswerIds)) {
                                        $color = "red";
                                    }
                                    if ($_POST['trainingMode'] == 1) {
                                        if (in_array($j, $writeOptions)) {
                                            $color = "green";
                                        }
                                        if (in_array($question[$option_key], $selectAnswerIds) && in_array($j, $writeOptions)) {
                                            $color = "yellow";
                                        }
                                    }
                                    $html .= '<div style="background-color:' . $color . '"><span>Вариант №' . $j . ':</span>' . $question[$option_key] . '</div>';
                                }
                            }
                            $html .= '</div>';
                        }
                        $html .= '</div></div>';
                        echo $html;
                    }
                    ?>
                    <div class="center">
                		<a href="index.php" class="btn btn-primary btn-lg">Назад к выбору теста</a>
                	</div>
                </div>
            </div>
        </div>
        <?php include('footer.php');
