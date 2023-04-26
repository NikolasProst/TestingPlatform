<?php 
$title = "Register";
include('header.php');
include('functions.php');
?>
        <div class="container content">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-6 col-sm-12">
                    <h1>Welcome to Online Test Platform</h1>
                    <img src="images/test.jpg" alt="online test">
                    <p>You can give various examinations on our website but you need to login first.</p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-sm-12">
                    <?php if(isset($_SESSION['error'])) : ?>
                    <span id="message">
                        <?php foreach ($_SESSION['error'] as $error) : ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div> 
                        <?php endforeach; 
                        unset($_SESSION['error']);  ?>
					</span>
					<?php endif; ?> 
							
					<?php if(isset($_SESSION['success'])) : ?>
                        <span id="message">
                            <div class="alert alert-success">
                                <?php echo $_SESSION['success']; 
							    unset($_SESSION['success']); ?>
                            </div> 
						</span>
					<?php endif; ?> 
                    <div class="card">
        		        <div class="card-header">Examinee Registration</div>
        		        <div class="card-body">
        			        <span id="message"></span>
                            <form action="addUser.php" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Enter Name</label>
                                    <input type="text" name="user_name" id="user_name" class="form-control" required/> 
                                </div>
                                <div class="form-group">
                                    <label>Enter Email Address</label>
                                    <input type="text" name="user_email" id="user_email" class="form-control" required/>
                                </div>
                                <div class="form-group">
                                    <label>Enter Password</label>
                                    <input type="password" name="password" id="password" class="form-control" required/>
                                </div>
                                <div class="form-group">
                                    <label>Enter Confirm Password</label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required/>
                                </div>
                                <div class="form-group">
                                    <label>Select Gender</label>
                                    <select name="gender" id="gender" class="form-control" required>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select> 
                                </div>
                                <div class="form-group">
                                    <label>Enter Mobile Number</label>
                                    <input type="text" name="mobile_no" id="mobile_no" class="form-control" required/> 
                                </div>
                                <div class="form-group">
                                    <label>Enter Address</label>
                                    <textarea name="address" id="address" class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Select Profile Image</label>
                                    <input type="file" name="image" id="image">
                                </div>
                                <br>
                                <div class="form-group center">
                                    <input type="submit" name="register" class="btn btn-info" value="Register">
                                </div>
                            </form>
          			        <div class="center">
                                Already a user? <a href="index.php">Login</a>
          			        </div>
        		        </div>
      		        </div>
                </div>
            </div>
        </div>
        <?php include('footer.php');

