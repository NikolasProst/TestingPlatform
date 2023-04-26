<?php
    $title = 'Создать предмет';
    include('checkAdminData.php');
    include('../config.php');
    include('../functions.php');

    if (isset($_POST['addSubj']))
    {
        $name = $_POST['subj_name'];
        $competence = $_POST['competence'];
        $sql = "INSERT INTO subjects (name) VALUES('".$name."')";

        if ($conn->query($sql) == true)
        {
            $_SESSION['success'] = "Предмет создан";
            header('location: subj_list.php');
        } else {
            $_SESSION['error'][] = $conn->error;
        }
    }
?>

<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
                <li><a href="index.php"><em class="fa fa-home"></em></a></li>
                <li><a href="subj_list.php">Предметы</a></li>
                <li>Создать предмет</li>
            </ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Создание предмета</h1>
			</div>
		</div><!--/.row-->
		<div class="content-box"><!-- Start Content Box -->
				<div class="content-box-content">
					<?php if(isset($_SESSION['error'])) : ?>
                    <span id="message">
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']);  ?>
                        </div> 
					</span>

					<?php endif; ?> 
					<form action="" method="post"> 
						<!-- This is the target div. id must match the href of this div's tab -->
						<div class="form-group ">
                            <div class="form-group">
                                <label for="question">Название</label>
                                <input type="text" class="form-control" name="subj_name" id="subj_name" required/>
                            </div>
                            <div class="form-group">

 						    <button type="submit" class="btn btn-default" name="addSubj">Добавить</button>
					</form>
				</div> <!-- End .content-box-content -->
			</div> <!-- End .content-box -->
	</div>	<!--/.main-->

	<?php include('footer.php'); ?>