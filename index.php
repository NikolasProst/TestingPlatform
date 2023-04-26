<?php
$title = "Tests List";

include('config.php');
include('functions.php');
//check session variable is set
//if (!isset($_SESSION['userdata']['user_email'])) {
//    header('location: index.php');
//}
//$user_id = $_SESSION['userdata']['user_id'];

if (isset($_POST['startTest']))
{
    $specialization = $_POST['specialization'];
    $subject = $_POST['subject'];
    $competence = $_POST['competence'];
    $sql = "SELECT id FROM tests where id_specialization = '".$specialization."' and id_subject = '".$subject."' and id_competence = '".$competence."' ORDER BY RAND() limit 1";
    $result = $conn->query($sql);

    while ($test = $result ->fetch_assoc()) {
        if ($test['id'] != null) {
            header('location: exam.php?test_id=' . $test['id']);
        } else {
            $_SESSION['error'][] = $conn->error;
        }
    }
}

include('header.php');
?>
        <div class="container content">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h3 class="center">Добро пожаловать на платформу тестирования</h3>
                    <br>
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
                                        <div class="form-group col-md-auto">
                                            <label for="inputState">Направление</label>
                                            <select id="inputState" class="form-control" name="specialization" id="specialization" onchange="getSubject(this.value)" required>
                                                <option value="0">Выберите направление</option>
                                                <?php showSpec() ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-auto">
                                            <label for="inputState">Предмет</label>
                                            <select id="subjectdiv" class="form-control" name="subject" id="subject" onchange="getCompetence(this.value)" required>
                                                <option value="0">Выберите предмет</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-auto">
                                            <label for="inputState">Компетенция</label>
                                            <select id="competencediv" class="form-control" name="competence" id="competence" required>
                                                <option value="0">Выберите компетенцию</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-auto">
                                            <button type="submit" class="btn btn-primary" name="startTest">Перейти к тесту</button>
                                        </div>
                                    </div>


                                </form>
                        </div> <!-- End .content-box-content -->
                    </div>
                </div>
            </div>
        </div>
        <?php include('footer.php');

