<?php
    $title = 'Редактировать связь направления';
    include('checkAdminData.php');
    include('../config.php');
    include('../functions.php');

    if (isset($_POST['editConn']))
    {
        $subjId = $_POST['subject'];
        $specId = $_POST['spec'];
        $oldSubjId = $_POST['oldSubjId'];
        $oldSpecId = $_POST['oldSpecId'];
        $sql = "UPDATE specialization_to_subjects SET id_subject='".$subjId."', id_specialization = '".$specId."' WHERE id_subject='".$oldSubjId."' AND id_specialization=". $oldSpecId;
        if ($conn->query($sql) == true)
        {
            $_SESSION['success'] = "Связь отредактирована";
            header('location: connectSpecToSubj_list.php');

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
    $oldSpecId = $_GET['spec_id'];
?>

<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="index.php"><em class="fa fa-home"></em></a></li>
                <li><a href="connectSpecToSubj_list.php">Связи направлений</a></li>
                <li>Редактировать связь направления</li>
            </ol>
        </div><!--/.row-->

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Редактирование связи направления</h1>
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
                            <label for="inputState">Направление</label>
                            <select id="inputState" class="form-control" name="spec" id="spec" required>
                                <?php showSpecBy($oldSpecId); ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="inputState">Предмет</label>
                            <select id="inputState" class="form-control" name="subject" id="subject" required>
                                <?php showSubjectBy($oldSubjId); ?>
                            </select>
                        </div>

                        <input type="hidden" name="oldSubjId" value="<?php echo $oldSubjId?>">
                        <input type="hidden" name="oldSpecId" value="<?php echo $oldSpecId?>">

                        <button type="submit" class="btn btn-default" name="editConn">Сохранить изменения</button>
                    </div>
                </form>
            </div> <!-- End .content-box-content -->
        </div> <!-- End .content-box -->
    </div><!--/.main-->

<?php include('footer.php'); ?>