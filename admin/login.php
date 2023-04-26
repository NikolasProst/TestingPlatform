<?php
session_start();
$title = "Sign In";
include('../config.php');
include('../functions.php');
$errors = array(); 

if (isset($_POST['submit'])) {
    $email = isset($_POST['email'])?$_POST['email']:'';
    $password = isset($_POST['password'])?$_POST['password']:'';
	$remember = isset($_POST['remember'])?$_POST['remember']:'';
	
    $login = loginAdmin($email, $password, $remember);

} 

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">    
        <title>Online Exam Admin  | <?php echo ucfirst($title); ?></title>
        <link rel="stylesheet" type="text/css" href="css/login.css">
        <!-- Bootstrap -->
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">   
        <script src="../js/bootstrap.min.js"></script>
    </head>
  
	<body>
		<div class="login-wrapper">
			<h3>Admin Login</h3>
			<?php if (sizeof($errors) > 0) : ?>
            <span id="message">
                <?php foreach ($errors as $error) : ?>
                <div class="alert alert-danger">
                <?php echo $error['msg']; ?>
                </div> 
                <?php endforeach; ?>
            </span>
            <?php endif; ?>
			<form class="loginForm" action="" method="post">
				<input placeholder="Email" type="email"  name="email" value="<?php if(isset($_COOKIE["admin_email"])) { echo $_COOKIE["admin_email"]; } ?>" required />
        		<input placeholder="Пароль" type="password" name="password" value="<?php if(isset($_COOKIE["admin_password"])) { echo $_COOKIE["admin_password"]; } ?>" required />
        		<div class="boxcheckbox">
        			<input type="checkbox" id="remember" name="remember" value="Запомнить меня" <?php if(isset($_COOKIE["admin_email"])) { ?> checked <?php } ?>/>
          			<label for="remember"> Remember me</label><br />
          			<input class="loginbtn" type="submit" name="submit" value="Войти" />
        		</div>
      		</form>
    	</div><!-- End #login-wrapper -->
  	</body>
</html>
