<?php
    $title = 'Добавить направление';
    include('checkAdminData.php');
    include('../config.php');
    include('../functions.php');

    if (isset($_POST['addSpec']))
    {
        $name = $_POST['spec_name'];
        $subject = $_POST['subject'];

        $sql = "INSERT INTO specializations (name) VALUES('".$name."')";

        if ($conn->query($sql) == true)
        {
            $_SESSION['success'] = "Направление создано";
            header('location: spec_list.php');

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
                <li><a href="spec_list.php">Направления</a></li>
                <li>Создать направление</li>
            </ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Создание направления</h1>
			</div>
		</div><!--/.row-->
		<div class="content-box"><!-- Start Content Box -->
				<div class="content-box-content">
					<?php if(isset($_SESSION['error'])) : ?>
                    <span id="message">
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error'][0]; unset($_SESSION['error']);  ?>
                        </div> 
					</span>

					<?php endif; ?> 
					<form action="" method="post"> 
						<!-- This is the target div. id must match the href of this div's tab -->
						<div class="form-group ">
                            <div class="form-group">
                                <label for="question">Название направления</label>
                                <input type="text" class="form-control" name="spec_name" id="spec_name" required/>
                            </div>
                        </div>

 						    <button type="submit" class="btn btn-default" name="addSpec">Добавить</button>
					</form>
				</div> <!-- End .content-box-content -->
			</div> <!-- End .content-box -->
	</div>	<!--/.main-->

	<?php include('footer.php'); ?>