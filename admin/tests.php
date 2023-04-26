<?php 
    $title = 'Tests';
    include('../config.php');
    include('../functions.php');
    include('checkAdminData.php');
    include('header.php');
?>  
<?php include('sidebar.php'); ?>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
    </script><div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="index.php"><em class="fa fa-home"></em></a></li>
            <li class="active">Тесты</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Список тестов</h1>
        </div>
    </div><!--/.row-->
    <div class="content-box"><!-- Start Content Box -->
        <div class="content-box-content">
            <div class="table-responsive">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="addTest.php" class="btn btn-info add-new"><i class="fa fa-plus"></i> Добавить тест</a>
                            </div>
                        </div>
                        <?php if(isset($_SESSION['error'])) : ?>
                            <span id="message">
                                <div class="alert alert-danger">
                                    <?php echo $_SESSION['error']; unset($_SESSION['error']);  ?>
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
                                            <td style="min-width:140px;">
                                                <a class="edit" href="viewTest.php?test_id=<?php echo $test_id;  ?>" data-toggle="tooltip" title="Просмотр теста"><i class="fa fa-eye"></i></a>
                                                <a class="addd" href="addQuestion.php?test_id=<?php echo $test_id; ?>" data-toggle="tooltip" title="Добавить вопрос"><i class="fa fa-plus"></i></a>
                                                <a class="delete" href="deleteTest.php?action=remove&test_id=<?php echo $test_id; ?>" data-toggle="tooltip" title="Удалить тест"><i class="fa fa-trash"></i></a>
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
</div>	<!--/.main--><script>$(document).ready(function(){$('#status').change(function(){
        if($(this).prop('checked')) {
            $('#hidden_status').val('enable');
            document.getElementById('enable_disable').submit();
        }
        else  {
            $('#hidden_status').val('disable');
            document.getElementById('enable_disable').submit();
        }
    });});
</script>

<?php include('footer.php'); ?>