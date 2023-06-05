<?php 
    $title = 'Add Question';
    include('../config.php');
    include('../functions.php');
    include('checkAdminData.php');
    include('header.php');
?>  
	<?php include('sidebar.php'); ?>
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="index.php">
					<em class="fa fa-home"></em>
				</a></li>
                <li><a href="test_list.php">Тесты</a></li>
                <li>Добавить вопрос</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Добавить вопрос</h1>
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
							
					<?php if(isset($_SESSION['success'])) : ?>
                        <span id="message">
                            <div class="alert alert-success">
                                <?php echo $_SESSION['success']; 
							    unset($_SESSION['success']); ?>
                            </div> 
						</span>
					<?php endif; ?>
                    <form id="addForm" action="add_quiz.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                        <!-- This is the target div. id must match the href of this div's tab -->
                        <div class="form-group col-md-12">
                            <div class="row">
                                <div class="form-group col-md-9">
                                    <label for="question">Вопрос</label>
                                    <input type="text" class="form-control" name="question" id="question" required/>
                                </div>

                                <div class="form-group col-md-3">
                                    <div class="form-check" style="margin-top: 45px;">
                                        <input type="checkbox" class="form-check-input"  value="Yes" name="question_type" id="question_type" onclick="clickCheckBox()">
                                        <label class="form-check-label" for="exampleCheck1">Свободный ответ</label>
                                    </div>
                                </div>
                            </div>

                            <div id="optionDiv">
                                <div class="form-group" id="div_option_1">
                                    <input type="checkbox" class="form-check-input" name="writeOption[]" value="1"/>
                                    <label for="option_1" id="label_option1">Вариант №1</label>
                                    <input type="text" class="form-control" name="option[]"/>
                                </div>
                                <div class="form-group" id="div_option_2">
                                    <input type="checkbox" class="form-check-input" name="writeOption[]" value="2"/>
                                    <label for="option_2" id="label_option2">Вариант №2</label>
                                    <input type="text" class="form-control" name="option[]"/>
                                </div>
                            </div>

                            <div class="form-group" id="answer" style="display: none">
                                <label for="question">"Эталонный ответ"</label>
                                <input type="text" class="form-control" name="answer"/>
                            </div>

                            <div class="form-group">
                                <label for="question">Загрузить картинку для вопроса</label>
                                <input type="file" class="form-control" name="picture"/>
                            </div>

                            <div class="form-group" style="display: none">
                                <label for="test_id">Номер теста</label>
                                <input type="number" class="form-control" name="exam_no" id="exam_no" min="1" placeholder="Номер теста" value="<?php if(isset($_GET['test_id'])) : echo $_GET['test_id'];  endif; ?>"required/>
                            </div>
                        </div>

                        <button type="button" class="btn btn-default" name="addOptionButton" onclick="addOption()">Добавить вариант ответа</button>
                        <button type="button" class="btn btn-default" name="delOptionButton" onclick="delOption()">Удалить вариант ответа</button>
                        <button type="submit" class="btn btn-default" name="quiz">Сохранить</button>
                    </form>
				</div> <!-- End .content-box-content -->
			</div> <!-- End .content-box -->

        <script type="text/javascript">

            var checkBoxState = false;

            function clickCheckBox()
            {
                checkBoxState = !checkBoxState;
                hideOptions();
            }

            function hideOptions()
            {
                var optionDiv = document.getElementById('optionDiv');
                var answer = document.getElementById('answer');

                if(checkBoxState) {
                    optionDiv.style.display = 'none';
                    answer.style.display = 'block';
                }
                else {
                    optionDiv.style.display = 'block';
                    answer.style.display = 'none';
                }
            }

            function validateForm() {
                var freeAnswer = document.getElementById('question_type').checked;

                if(!freeAnswer) {
                    var options = document.getElementsByName("writeOption[]");
                    var checked = false;

                    for (var i = 0; i < options.length; i++) {
                        if (options[i].checked) {
                            checked = true;
                            break;
                        }
                    }
                    if (!checked) {
                        alert("Выберите хотя бы один вариант ответа");
                        return false;
                    }

                    var optionTexts = document.getElementsByName("option[]");
                    for (var i = 0; i < optionTexts.length; i++) {
                        if (optionTexts[i].value.trim() === "") {
                            alert("Заполните все варианты ответа");
                            return false;
                        }
                    }
                }

                var answerTexts = document.getElementsByName("answer")[0].value.trim();
                if (freeAnswer && answerTexts === "") {
                    alert("Заполните эталонный ответ");
                    return false;
                }

                var questionText = document.getElementById("question").value.trim();
                if (questionText === "") {
                    alert("Заполните поле 'Вопрос'");
                    return false;
                }

                return true;
            }
        </script>

    </div>	<!--/.main-->
	<?php include('footer.php'); ?>