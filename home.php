<?php
$title = "Homepage";
include('header.php');
include('functions.php');
$errors = array(); 

if (isset($_POST['login'])) {
   // $user_email = isset($_POST['user_email'])?$_POST['user_email']:'';
    //$password = isset($_POST['password'])?$_POST['password']:'';
	//$remember = isset($_POST['remember'])?$_POST['remember']:'';
    //$login = loginUser($user_email, $password, $remember);
} 
?>
        <div class="container content">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-6 col-sm-12">
                    <h1>Welcome to Online Test Platform</h1>
                    <img src="images/test.jpg" alt="online test">
                    <p>You can give various examinations on our website but you need to login first.</p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-sm-12">
                    <?php if (sizeof($errors) > 0) : ?>
                    <span id="message">
                        <?php foreach ($errors as $error) : ?>
                        <div class="alert alert-danger">
                            <?php echo $error['msg']; ?>
                        </div> 
                        <?php endforeach; ?>
                    </span>
                    <?php endif; ?>
                    <div class="card">
                        <div class="card-header">Examinee Login</div>
                        <div class="card-body">
                        
                            <form action="" method="post">
                                <div class="form-group">
                                    <label>Enter Email Address</label>
                                    <input type="email" name="user_email" class="form-control" value="<?php if(isset($_COOKIE["useremail"])) { echo $_COOKIE["useremail"]; } ?>" required />
                                </div>
                                <div class="form-group">
                                    <label>Enter Password</label>
                                    <input type="password" name="password" class="form-control" value="<?php if(isset($_COOKIE["password"])) { echo $_COOKIE["password"]; } ?>" required />
                                </div>
                                <div class="form-group">
                                    <p id="remember-password">
						                <input type="checkbox" name="remember" <?php if(isset($_COOKIE["useremail"])) { ?> checked <?php } ?> /> Remember me
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="login" class="btn btn-info" value="Login">
                                </div>
                            </form>
                             <div class="center">
                                Not registered yet? <a href="register.php">Register</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('footer.php');

