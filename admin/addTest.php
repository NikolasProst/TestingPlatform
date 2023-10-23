<?php
$title = 'Add Test';
include('checkAdminData.php');
include('../config.php');
include('../functions.php');

if (isset($_POST['addtest']))
{
    $test_name = $_POST['test_name'];
    $specialization = $_POST['specialization'];
    $subject = $_POST['subject'];
    $competence = $_POST['competence'];
    $is_common_test = isset($_POST['is_common_test']) ? 1 : 0;
    $sql = "INSERT INTO tests (test_title, id_specialization, id_subject, id_competence, date) VALUES('".$test_name."', '".$specialization."', '".$subject."', '".$competence."', now())";

    if ($conn->query($sql) == true)
    {
        $_SESSION['success'] = "Тест успешно создан";
        header('location: index.php');
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
                <li><a href="index.php">
                        <em class="fa fa-home"></em>
                    </a></li>
                <li><a href="index.php">Тесты</a></li>
                <li>Создать тест</li>
            </ol>
        </div><!--/.row-->

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Создание теста</h1>
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
                    <div class="form-group">
                        <label for="question">Название</label>
                        <input type="text" class="form-control" name="test_name" id="test_name" required/>
                    </div>

                    <div class="form-group">
                        <label for="specialization">Направление</label>
                        <select id="specialization" class="form-control" name="specialization" onchange="getSubjectsAndCompetences(this.value)" required>
                            <option value="0">Выберите направление</option>
                            <?php showSpec() ?>
                        </select>
                    </div>

                    <div id="subjectAndCompetence">
                        <div class="form-group">
                            <label for="subject">Предмет</label>
                            <select id="subject" class="form-control" name="subject" onchange="filterCompetence(this.value)">
                                <option value="0">Выберите предмет</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="competence">Компетенция</label>
                            <select id="competence" class="form-control" name="competence" onchange="filterSubject(this.value)" required>
                                <option value="0">Выберите компетенцию</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-default" name="addtest">Добавить</button>
                </form>
            </div> <!-- End .content-box-content -->
        </div> <!-- End .content-box -->
    </div>  <!--/.main-->

<?php include('footer.php');