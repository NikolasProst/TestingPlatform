<?php
    $title = 'Edit Test';
    include('checkAdminData.php');
    include('../config.php');
    include('../functions.php');

    if (isset($_POST['editTest']))
    {
        $testId = $_POST['testId'];
        $testName = $_POST['test_name'];
        $specialization = $_POST['specialization'];
        $subject = $_POST['subject'];
        $competence = $_POST['competence'];

        if ($specialization == 0 && $subject == 0 && $competence == 0)
        {
            $sql = "UPDATE tests SET test_title='".$testName."' WHERE id=".$testId;
        }
        else
        {
            $sql = "UPDATE tests SET test_title='".$testName."', id_specialization='".$specialization."', id_subject='".$subject."', id_competence='".$competence."'
                        WHERE id=".$testId;
        }

        if ($conn->query($sql) == true)
        {
            $_SESSION['success'] = "Тест отредактирован";
            header('location: index.php');
        } else {
            $_SESSION['error'][] = $conn->error;
        }
    }

    $testId = $_GET['test_id'];
    $sql = "SELECT * FROM tests WHERE id=".$testId;
    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $_SESSION['error'][] = $conn->error;
    }
    ?>

    <?php include('header.php'); ?>
    <?php include('sidebar.php'); ?>

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="index.php"><em class="fa fa-home"></em></a></li>
            <li><a href="test_list.php">Тесты</a></li>
            <li>Редактировать тест</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Редактирование теста</h1>
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
                        <input type="text" class="form-control" name="test_name" id="test_name" value="<?php echo $row['test_title']; ?>" required/>
                        <input type="hidden" name="testId" value="<?php echo $row['id']; ?>">
                    </div>
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
                            <?php showSubject() ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="competence">Компетенция</label>
                        <select id="competence" class="form-control" name="competence" onchange="filterSubject(this.value)" required>
                            <option value="0">Выберите компетенцию</option>
                            <?php showCompetence() ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-default" name="editTest">Сохранить изменения</button>
                <a href="index.php" class="btn btn-default">Отмена</a> <!-- Кнопка "Отмена" -->
            </form>
        </div> <!-- End .content-box-content -->
    </div> <!-- End .content-box -->
</div

<?php include('footer.php'); ?>