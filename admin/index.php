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
                    <div class="row">

                        <div class="col-md-10">
                            <form method="POST" class="form-inline" style="margin-bottom: 20px;">

                                <div class="form-group">
                                    <label for="specialization">Направление:</label>
                                    <select name="specialization" id="specialization" class="form-control" style="max-width: 200px">
                                        <option value="">Все</option>
                                        <?php
                                        $sql = "SELECT DISTINCT sp.name FROM tests t LEFT JOIN specializations sp ON t.id_specialization = sp.id";
                                        $result = $conn->query($sql);
                                        while($row = $result->fetch_assoc()) {
                                            if ($row['name'] != null)
                                                echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="subject">Предмет:</label>
                                    <select name="subject" id="subject" class="form-control" style="max-width: 200px">
                                        <option value="">Все</option>
                                        <?php
                                        $sql = "SELECT DISTINCT s.name FROM tests t LEFT JOIN subjects s ON t.id_competence = s.id";
                                        $result = $conn->query($sql);
                                        while($row = $result->fetch_assoc()) {
                                            if ($row['name'] != null)
                                                echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="competence">Компетенция:</label>
                                    <select name="competence" id="competence" class="form-control" style="max-width: 200px">
                                        <option value="">Все</option>
                                        <?php
                                        $sql = "SELECT DISTINCT c.name FROM tests t LEFT JOIN competences c ON t.id_competence = c.id";
                                        $result = $conn->query($sql);
                                        while($row = $result->fetch_assoc()) {
                                            if ($row['name'] != null)
                                                echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">Фильтр</button>
                                <a href="index.php" class="btn btn-secondary">Сбросить</a>

                            </form>
                        </div>

                        <div class="col-md-2">
                            <a href="addTest.php" class="btn btn-info add-new"><i class="fa fa-plus"></i> Добавить тест</a>
                        </div>

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
                            <th>Дата создания</th>
                            <th style="min-width:140px;">Действия</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $sql = "SELECT t.id, t.test_title, t.date, s.name subjectName, sp.name specializationName, c.name competenceName, COUNT(q.id) questionCount
                                    FROM tests t 
                                    LEFT JOIN subjects s ON t.id_subject = s.id 
                                    LEFT JOIN specializations sp ON t.id_specialization = sp.id 
                                    LEFT JOIN competences c ON t.id_competence = c.id 
                                    LEFT JOIN questions q ON q.id_test = t.id ";

                        if(isset($_POST['subject']) && !empty($_POST['subject'])) {
                            $subject = $_POST['subject'];
                            $sql .= "WHERE s.name = '$subject' ";
                        }
                        if(isset($_POST['specialization']) && !empty($_POST['specialization'])) {
                            $specialization = $_POST['specialization'];
                            if(strpos($sql, 'WHERE')) {
                                $sql .= "AND sp.name = '$specialization' ";
                            } else {
                                $sql .= "WHERE sp.name = '$specialization' ";
                            }
                        }
                        if(isset($_POST['competence']) && !empty($_POST['competence'])) {
                            $competence = $_POST['competence'];
                            if(strpos($sql, 'WHERE')) {
                                $sql .= "AND c.name = '$competence' ";
                            } else {
                                $sql .= "WHERE c.name = '$competence' ";
                            }
                        }

                        $sql .= "GROUP BY t.id";

                        $result = $conn->query($sql);
                        $id_test = 1;

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {?>
                                <tr>
                                <td><?php echo $id_test;?></td>
                                <td><?php echo $row['test_title'];?></td>
                                <td><?php echo $row['specializationName'];?></td>
                                <td><?php echo $row['subjectName'];?></td>
                                <td><?php echo $row['competenceName'];?></td>
                                <td><?php echo $row['questionCount'];?></td>
                                <td><?php echo $row['date'];?></td>
                                <td style="min-width:140px;">
                                    <a class="edit" href="viewTest.php?test_id=<?php echo $row['id'];  ?>" data-toggle="tooltip" title="Просмотр теста"><i class="fa fa-eye"></i></a>
                                    <a class="add" href="addQuestion.php?test_id=<?php echo $row['id']; ?>" data-toggle="tooltip" title="Добавить вопрос"><i class="fa fa-plus"></i></a>
                                    <a class="copy" href="copyTest.php?test_id=<?php echo $row['id']; ?>" data-toggle="tooltip" title="Копировать тест"><i class="fa fa-copy"></i></a>
                                    <a style="float: right" class="delete" onclick="confirmDelete(<?php echo $row['id']; ?>)" data-toggle="tooltip" title="Удалить тест"><i class="fa fa-trash"></i></a>
                                </td>
                                </tr><?php
                                $id_test++;
                            }
                        }
                        ?>
                        </tbody
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
    function confirmDelete(testId) {
        if (confirm("Вы уверены, что хотите удалить этот тест?")) {
            window.location.href = "deleteTab.php?action=removeTest&test_id=" + testId;
        }
    }
</script>

<?php include('footer.php'); ?>