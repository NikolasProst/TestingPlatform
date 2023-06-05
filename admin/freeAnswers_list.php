<?php 
    $title = 'Предметы';
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
            <li class="active">Ответы на свободные вопросы</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Список ответов на свободные вопросы</h1>
        </div>
    </div><!--/.row-->
    <div class="content-box"><!-- Start Content Box -->
        <div class="content-box-content">
            <form action="" method="get">
                <div class="form-group">
                    <label for="question_filter">Фильтр по вопросу:</label>
                    <select name="question_filter" id="question_filter" class="form-control">
                        <option value="">Выберите вопрос</option>
                        <?php
                        $sql = "SELECT DISTINCT text FROM freeanswersstats";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $selected = '';
                                if (isset($_GET['question_filter']) && $_GET['question_filter'] == $row['text']) {
                                    $selected = 'selected';
                                }
                                echo '<option value="' . $row['text'] . '" ' . $selected . '>' . $row['text'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Фильтр</button>
                <a href="freeAnswers_list.php" class="btn btn-secondary">Сбросить</a>
            </form>
            <form action="saveFreeAnswers.php" method="post">
            <div class="table-responsive">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-info add-new"><i class="fa fa-plus"></i> Сохранить ответы</button>
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
                                <th>Вопрос</th>
                                <th>Эталонный ответ</th>
                                <th>Ответ пользователя</th>
                                <th>Правильный</th>
                                <th style="min-width:140px;">Действия</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $question_filter = isset($_GET['question_filter']) ? $_GET['question_filter'] : '';
                            $sql = "SELECT * FROM freeanswersstats WHERE text LIKE '%$question_filter%'";

                            $result = $conn->query($sql);
                            $id_ans = 1;

                            if ($result->num_rows > 0)
                            {
                                while ($row = $result->fetch_assoc()) {;?>
                                    <tr>
                                        <td><?php echo $id_ans;?></td>
                                        <td><?php echo $row['text'];?></td>
                                        <td><?php echo $row['writeAnswer'];?></td>
                                        <td><?php echo $row['userAnswer'];?></td>
                                        <td>
                                            <input type="checkbox" name="is_true[]" value="<?php echo $row['id']; ?>" <?php if ($row['is_true'] == '1') { echo 'checked'; } ?>>
                                        </td>
                                        <?php $id_ans++;?>
                                        <td style="min-width:140px;">
                                            <a class="delete" href="deleteTab.php?action=removeFreeAnswer&ans_id=<?php echo $row['id']; ?>" data-toggle="tooltip" title="Удалить вариант ответа"><i class="fa fa-trash"></i></a>
                                        </td>
                                        <input type="hidden" name="id_ans" value="<?php echo $row['id']; ?>">
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            </form>
            <div class="row">
                <div class="col-sm-12">
                    <button id="export-csv-btn" class="btn btn-success">Сделать выгрузку</button>
                </div>
            </div>
        </div> <!-- End .content-box-content -->
    </div> <!-- End .content-box -->
</div>	<!--/.main-->
    <script>$(document).ready(function(){$('#status').change(function(){
        if($(this).prop('checked')) {
            $('#hidden_status').val('enable');
            document.getElementById('enable_disable').submit();
        }
        else  {
            $('#hidden_status').val('disable');
            document.getElementById('enable_disable').submit();
        }
    });});

    $(document).ready(function() {
        $('#export-csv-btn').click(function() {
            // Send a GET request to the PHP script that generates the CSV file
            window.location.href = 'exportFreeAnswers.php';
        });
    });

</script>

<?php include('footer.php'); ?>