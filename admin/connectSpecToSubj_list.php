<?php 
    $title = 'Связи направлений';
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
            <li class="active">Связи направлений</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Список связей направлений</h1>
        </div>
    </div><!--/.row-->
    <div class="content-box"><!-- Start Content Box -->
        <div class="content-box-content">
            <div class="table-responsive">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="addConnectSpecToSubj.php" class="btn btn-info add-new"><i class="fa fa-plus"></i> Добавить связь направления</a>
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
                                <th>Напраление</th>
                                <th>Предмет</th>
                                <th style="min-width:140px;">Действия</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $sql = "select spec.id spec_id, spec.name speс_name, s.id subj_id, s.name subj_name from specializations spec left join specialization_to_subjects sts on spec.id = sts.id_specialization left join subjects s on sts.id_subject = s.id order by spec.name;";
                                $result = $conn->query($sql);
                                $id = 1;

                                if ($result->num_rows > 0)
                                {
                                    // output data of each row
                                    while ($row = $result->fetch_assoc()) {;?>
                                        <tr>
                                            <?php if ($row['subj_name'] != null) {?>
                                        <td><?php echo $id;?></td>
                                        <td><?php echo $row['speс_name'];?></td>
                                        <td><?php echo $row['subj_name'];?></td>
                                            <?php $id++;?>
                                        <td style="min-width:140px;">
                                            <a class="delete" href="deleteTab.php?action=removeConSts&spec_id=<?php echo $row['spec_id']; ?>&subj_id=<?php echo $row['subj_id']; ?>" data-toggle="tooltip" title="Удалить направление"><i class="fa fa-trash"></i></a>
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