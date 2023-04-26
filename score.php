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
                	$total_question = $answer['right']+$answer['wrong']+$answer['no_answer'];
                	$attempt_question = $answer['right']+$answer['wrong'];
                	?>
                	<h1>
                		<?php
                        $sql = "SELECT * FROM tests WHERE id='".$answer['test_id']."'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        echo $row['test_title'];?> (Тест <?php echo $answer['test_id']; ?>) Результат:
                	</h1><br/>
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
						echo '<h4 class="center">🙂🥳 Вы прошли тест, Ваш результат '.$percentage.'%.</h4>';
					}
                	?>
                	<div class="center">
                		<!--<a href="result.php" class="btn btn-success btn-lg">Check Your Answers</a>-->
                		<a href="index.php" class="btn btn-primary btn-lg">Назад к выбору теста</a>
                	</div>
                </div>
            </div>
        </div>
        <?php include('footer.php');
