<?php 
$title = "Score";
include('header.php');
include_once('config.php');
include_once('functions.php');
//check session variable is set
if (!isset($_SESSION['userdata']['user_email'])) {
    header('location: index.php');
}
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
                        $sql = "SELECT * FROM exam WHERE exam_id='".$answer['exam_no']."'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc(); 
                        echo $row['exam_title'];?> (Test <?php echo $answer['exam_no']; ?>) Result
                	</h1><br/>
                	<table class="table table-bordered">
					    <thead>
					    	<tr>
					    		<th>Total Number Of Questions</th>
					            <th><?php echo $total_question; ?></th>
					      </tr>
					    </thead>
					    <tbody>
					      	<tr>
					        	<td>Attempted Questions</td>
					        	<td><?php echo $attempt_question; ?></td>
					      	</tr>
					      	<tr>
					        	<td>Right Answer</td>
					        	<td><?php echo $answer['right']; ?></td>
					      	</tr>
					      	<tr>
					        	<td>Wrong Answer</td>
					        	<td><?php echo $answer['wrong']; ?></td>
					      	</tr>
					      	<tr>
					        	<td>No Answer</td>
					        	<td><?php echo $answer['no_answer']; ?></td>
					      	</tr>
					      	<tr class="result">
					        	<td>Your Result</td>
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
						echo '<h4 class="center">😓 You need to score at least 50% to pass the exam.</h4>';
					}
					else {
						echo '<h4 class="center">🙂🥳 You have passed the exam and scored '.$percentage.'%.</h4>';
					}
                	?>
                	<div class="center">
                		<!--<a href="result.php" class="btn btn-success btn-lg">Check Your Answers</a>-->
                		<a href="index.php" class="btn btn-primary btn-lg">Back To Tests</a>
                	</div>
                </div>
            </div>
        </div>
        <?php include('footer.php');
