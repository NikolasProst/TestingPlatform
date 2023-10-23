<?php
    $title = 'Редактировать связь предмета';
    include('checkAdminData.php');
    include('../config.php');
    include('../functions.php');

    if (isset($_POST['editConn']))
    {
        $subjId = $_POST['subject'];
        $compId = $_POST['comp'];
        $oldSubjId = $_POST['oldSubjId'];
        $oldCompId = $_POST['oldCompId'];
        $sql = "UPDATE subjects_to_competence SET id_subject='".$subjId."', id_competence = '".$compId."' WHERE id_subject='".$oldSubjId."' AND id_competence=". $oldCompId;
        if ($conn->query($sql) == true)
        {
            $_SESSION['success'] = "Связь отредактирована";
            header('location: connectSubjToComp_list.php');

        } else {
            if (strpos($conn->error, "Duplicate entry") !== false) {
                // Здесь обрабатываем случай дублированной записи
                $_SESSION['error'][] = "Ошибка: запись уже существует.";
            } else {
                // Здесь обрабатываем другие ошибки
                $_SESSION['error'][] = $conn->error;
            }
        }
    }


    $oldSubjId = $_GET['subj_id'];
    $oldCompId = $_GET['comp_id'];
?>

<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="index.php"><em class="fa fa-home"></em></a></li>
                <li><a href="connectSubjToComp_list.php">Связи предметов</a></li>
                <li>Редактировать связь предмета</li>
            </ol>
        </div><!--/.row-->

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Редактирование связи предмета</h1>
            </div>
        </div><!--/.row-->
        <div class="content-box"><!-- Start Content Box -->
            <div class="content-box-content">
                <?php if(isset($_SESSION['error'])) : ?>
                    <span id="message">
                    <div class="alert alert-danger">
                        <?php echo($_SESSION['error'][0]); unset($_SESSION['error']);  ?>
                    </div>
                </span>
                <?php endif; ?>
                <form action="" method="post">
                    <!-- This is the target div. id must match the href of this div's tab -->
                    <div class="form-group ">

                        <div class="form-group">
                            <label for="inputState">Предмет</label>
                            <select id="inputState" class="form-control" name="subject" id="subject" required>
                                <?php showSubjectBy($oldSubjId); ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="inputState">Компетенция</label>
                            <select id="inputState" class="form-control" name="comp" id="comp" required>
                                <?php showCompetenceBy($oldCompId); ?>
                            </select>
                        </div>

                        <input type="hidden" name="oldSubjId" value="<?php echo $oldSubjId?>">
                        <input type="hidden" name="oldCompId" value="<?php echo $oldCompId?>">

                        <button type="submit" class="btn btn-default" name="editConn">Сохранить изменения</button>
                    </div>
                </form>
            </div> <!-- End .content-box-content -->
        </div> <!-- End .content-box -->
    </div><!--/.main-->

<?php include('footer.php'); ?>