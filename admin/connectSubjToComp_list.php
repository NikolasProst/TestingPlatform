<?php 
    $title = 'Связи предметов';
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
            <li class="active">Связи предметов</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Список связей предметов</h1>
        </div>
    </div><!--/.row-->
    <div class="content-box"><!-- Start Content Box -->
        <div class="content-box-content">
            <div class="table-responsive">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="addConnectSubjToComp.php" class="btn btn-info add-new"><i class="fa fa-plus"></i> Добавить связь предмета</a>
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
                                <th>Предмет</th>
                                <th>Компетенция</th>
                                <th style="min-width:140px;">Действия</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $sql = "select s.id subj_id, s.name subj_name, c.id comp_id, c.name comp_name from subjects s left join subjects_to_competence stc on s.id = stc.id_subject left join  competences c on stc.id_competence = c.id order by s.name";
                                $result = $conn->query($sql);
                                $id = 1;

                                if ($result->num_rows > 0)
                                {
                                    // output data of each row
                                    while ($row = $result->fetch_assoc()) {;?>
                                        <tr>
                                            <?php if ($row['comp_name'] != null) {?>
                                        <td><?php echo $id;?></td>
                                        <td><?php echo $row['subj_name'];?></td>
                                        <td><?php echo $row['comp_name'];?></td>
                                            <?php $id++;?>
                                        <td style="min-width:140px;">
                                            <a class="delete" href="deleteTab.php?action=removeConStc&subj_id=<?php echo $row['subj_id']; ?>&comp_id=<?php echo $row['comp_id'];?>" data-toggle="tooltip" title="Удалить направление"><i class="fa fa-trash"></i></a>
                                        </td>
                                            <?}?>
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