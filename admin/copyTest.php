<?php

    $title = 'Copy Test';
    include('checkAdminData.php');
    include('../config.php');
    include('../functions.php');

    $testId = $_GET['test_id'];
    $sql = "SELECT * FROM tests WHERE id=".$testId;
    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $_SESSION['error'][] = $conn->error;
    }

    if (isset($_POST['editTest']))
    {
        $testId = $_POST['testId'];
        $testName = $_POST['test_name'];
        $specialization = $_POST['specialization'];

        $sql = "INSERT INTO tests (test_title, id_specialization, id_subject, id_competence, date) VALUE ('".$testName."', '".$specialization."', '".$row['id_subject']."', '".$row['id_competence']."', now())";

        if ($conn->query($sql) == true)
        {
            $lastIdTest = getLastIdTest();

            $sql = "insert into questions (id_test, text, option_1, option_2, option_3, option_4, answer_for_free, write_options, image, type)
                        select " . $lastIdTest . ", text, option_1, option_2, option_3, option_4, answer_for_free, write_options, image, type 
                            from questions where id_test = " . $testId;

            if ($conn->query($sql) == true)
            {
                $_SESSION['success'] = "Тест успешно скопирован";
                header('location: index.php');
            }
            else {
                $_SESSION['error'][] = $conn->error;
            }
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
            <li><a href="test_list.php">Тесты</a></li>
            <li>Копировать тест</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Копирование теста</h1>
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
                <div class="form-group ">
                    <div class="form-group">
                        <label for="question">Название</label>
                        <input type="text" class="form-control" name="test_name" id="test_name" value="<?php echo $row['test_title']; ?>" required/>
                        <input type="hidden" name="testId" value="<?php echo $row['id']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="specialization">Направление</label>
                    <select id="specialization" class="form-control" name="specialization" required>
                        <?php showSpecWithSelected($row['id_specialization']) ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-default" name="editTest">Создать копию</button>
                <a href="index.php" class="btn btn-default">Отмена</a>
            </form>
        </div>
    </div>
</div

<?php include('footer.php'); ?>
