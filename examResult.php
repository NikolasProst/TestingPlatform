<?php 
$title = "Result";
include('header.php');
include_once('config.php');
include_once('functions.php');
//check session variable is set
if (!isset($_SESSION['userdata']['user_email'])) {
    header('location: index.php');
}
$user_id = $_SESSION['userdata']['user_id'];
$test_id = $_GET['exam_no'];
?>
        <div class="container content score">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                	<h1>
                		<?php
                        $sql = "SELECT * FROM exam WHERE exam_id='".$test_id."'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc(); 
                        echo $row['exam_title'];?> (Test <?php echo $test_id; ?>) Result
                	</h1><br/>
                	<table class="table table-bordered">
					    <thead>
					    	<tr>
					    		<th>Total Number Of Questions</th>
					            <th>
					            	<?php 
                                    $sql1 = "SELECT * FROM questions WHERE exam_no='".$test_id."'";
                                    $result1 = $conn->query($sql1);
                                    $count = $result1->num_rows;
                                    echo $count;
                                    ?></th>
					      </tr>
					    </thead>
					    <tbody>
							<?php 
							$sql2 = "SELECT * FROM user_exam_result WHERE exam_id='".$test_id."' && user_id='".$user_id."'";
							$result2 = $conn->query($sql2);
							if ($result2->num_rows > 0) {
                                // output data of each row
                                while ($row1 = $result2->fetch_assoc()) {
									$mark = $row1['marks'];
									$marks = explode (",", $mark);
								}
							}

					    	?>
					      	<tr>
					        	<td>Attempted Questions</td>
					        	<td><?php echo $attempt_question; ?></td>
					      	</tr>
					      	<tr>
					        	<td>Right Answer</td>
					        	<td><?php echo $row['marks']; ?></td>
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
                		<a href="home.php" class="btn btn-primary btn-lg">Back To Tests</a>	
                	</div>
                </div>
            </div>
        </div>
        <?php include('footer.php');
