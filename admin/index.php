<?php 
$title = 'Dashboard';
include('../config.php');
include('../functions.php');
include('checkAdminData.php');
include('header.php'); 
?>  
	<?php include('sidebar.php'); ?>
		
	<div class="col-sm-10 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="index.php">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">Главная</li>
			</ol>
		</div><!--/.row-->
		<br/><br/>
		<div class="content-box"><!-- Start Content Box -->
				<div class="content-box-content">
					<div class="table-responsive">
        				<div class="table-wrapper">
            				<div class="table-title">
                				<h2>Тесты</h2>
            				</div>
            				<table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Название</th>
                                        <th>Направление</th>
                                        <th>Предмет</th>
                                        <th>Компетенция</th>
                                        <th>Количество вопросов</th>
                                        <th style="min-width:140px;">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql = "select t.id, t.test_title, s.name subjectName, sp.name specializationName, c.name competenceName from tests t
                                    left join subjects s on id_competence = s.id
                                    left join specializations sp on id_specialization = sp.id
                                    left join  competences c on id_competence = c.id";
                                $result = $conn->query($sql);
                                $id_test = 1;

                                if ($result->num_rows > 0)
                                {
                                    // output data of each row
                                    while ($row = $result->fetch_assoc()) {?>
                                        <tr>
                                        <td><?php echo $id_test;?></td>
                                        <td><?php echo $row['test_title'];?></td>
                                        <td><?php echo $row['specializationName'];?></td>
                                        <td><?php echo $row['subjectName'];?></td>
                                        <td><?php echo $row['competenceName'];?></td>
                                        <td>
                                            <?php
                                            $sql1 = "SELECT q.id FROM questions q RIGHT JOIN tests t ON q.id_test = t.id WHERE q.id_test ='".$row['id']."'";
                                            $result1 = $conn->query($sql1);
                                            $test_id = $row['id'];
                                            $count = $result1->num_rows;
                                            echo $count;
                                            $id_test++;
                                            ?>
                                        </td>
                                        <td>
                                            <a href="viewTest.php?test_id=<?php echo $row["id"]; ?>" >Показать тест</a>
                                        </td>
                                        </tr><?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
				        </div>
    				</div>
				</div> <!-- End .content-box-content -->
			</div> <!-- End .content-box -->
		<div class="content-box"><!-- Start Content Box -->
				<div class="content-box-content">
					<div class="table-responsive">
        				<div class="table-wrapper">
            				<div class="table-title">
                				<h2>Статистика</h2>
            				</div>
            				<table class="table table-bordered">

                            </table>
				        </div>
    				</div>
				</div> <!-- End .content-box-content -->
			</div> <!-- End .content-box -->

	</div>	<!--/.main-->
	
	<?php include('footer.php'); ?>