<?php 
$title = 'View Test';
include('../config.php');
include('../functions.php');
include('checkAdminData.php');
include('header.php'); 
$test_id = $_GET['test_id'];
?>  
	<?php include('sidebar.php'); ?>
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="index.php">
					<em class="fa fa-home"></em>
				</a></li>
                <li><a href="tests.php">Тесты</a></li>
				<li class="active">Просмотр теста</li>
			</ol>

		</div><!--/.row-->
		<br/><br/>
		<div class="content-box"><!-- Start Content Box -->
			<div class="content-box-content">
                <?php
                    showQuestions();
                ?>
			</div> <!-- End .content-box-content -->
		</div> <!-- End .content-box -->
	</div>	<!--/.main-->
	
	<?php include('footer.php'); ?>