<?php 
$title = 'Users';
include('../config.php');
include('../functions.php');
include('header.php'); 
?>  
	<?php include('sidebar.php'); ?>
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="index.php">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">Users</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Users Detail</h1>
			</div>
		</div><!--/.row-->
		<div class="content-box"><!-- Start Content Box -->
				<div class="content-box-content">
					<div class="table-responsive">
        				<div class="table-wrapper">
            				<div class="table-title"><br/>
                				<?php if(isset($_SESSION['error'])) : ?>
                    			<span id="message">
                        			<div class="alert alert-danger">
                            			<?php echo $_SESSION['error'][0]; unset($_SESSION['error']);  ?>
                        			</div> 
								</span>
								<?php endif; ?> 
							
								<?php if(isset($_SESSION['success'])) : ?>
                        		<span id="message">
                            		<div class="alert alert-success">
                                		<?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                            		</div> 
								</span>
								<?php endif; ?> 
            				</div>
            				<table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Gender</th>
                                        <th>Mobile No.</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql = "SELECT * FROM users";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    // output data of each row
                                    while ($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                	<td><?php echo $row['user_id'];?></td>
                                    <td><a href="../images/<?php echo $row['image'];?>"><img width="60px" height="60px" src="../images/<?php echo $row['image'];?>" alt="image" /></a> <?php echo $row['name'];?></td>
                                    <td><?php echo $row['email'];?></td>
                                    <td><?php echo $row['gender'];?></td>
                                    <td><?php echo $row['mobile_no'];?></td>
                                    <td><?php echo $row['address'];?></td>
									<td>
				                        <a class="delete" href="deleteUser.php?action=remove&user_id=<?php echo $row["user_id"]; ?>" title="Delete User" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash"></i></a>
				                    </td>
                                </tr>
                                <?php
                                    }
                                } 
                                ?>
                                 
                                </tbody>
                            </table>
				        </div>
    				</div>
				</div> <!-- End .content-box-content -->
			</div> <!-- End .content-box -->
	</div>	<!--/.main-->
	
	<?php include('footer.php'); ?>