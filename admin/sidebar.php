<?php
    $url = $_SERVER['REQUEST_URI'];
    $path = parse_url($url, PHP_URL_PATH);
    $filename = basename($path);
    $test_menu = array('index.php', 'addTest.php', 'viewTest.php', 'addQuestion.php');
    $user_menu = array('testResult.php', 'users.php');
?>

<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
	<ul class="nav menu">
		<li class="parent <?php if(in_array($filename,$test_menu)): ?>current<?php endif; ?>">
			<a data-toggle="collapse" href="#sub-item-1">
				<em class="fa fa-check-square-o">&nbsp;</em> Тесты<span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="fa fa-plus"></em></span>
			</a>
			<ul class="children collapse" id="sub-item-1">
				<li>
					<a href="index.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Просмотр тестов
					</a>
				</li>
                <li>
					<a href="addTest.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Добавить тест
					</a>
				</li>
			</ul>

        <li><a href="spec_list.php"><span class="fa fa-list-alt">&nbsp;</span> Список направлений</a></li>

        <li><a href="subj_list.php"><span class="fa fa-book">&nbsp;</span> Список предметов</a></li>

        <li><a href="comp_list.php"><span class="fa fa-briefcase">&nbsp;</span> Список компетенций</a></li>

        <li class="parent <?php if(in_array($filename,$test_menu)): ?>current<?php endif; ?>">
			<a data-toggle="collapse" href="#sub-item-5">
				<em class="fa fa-arrows-h">&nbsp;</em> Настройка связей<span data-toggle="collapse" href="#sub-item-5" class="icon pull-right"><em class="fa fa-plus"></em></span>
			</a>
			<ul class="children collapse" id="sub-item-5">
                <li>
					<a href="connectSpecToSubj_list.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Связи направлений
					</a>
				</li>
                <li>
					<a href="connectSubjToComp_list.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Связи предметов
					</a>
				</li>
			</ul>
		</li>		</li>

        <li><a href="freeAnswers_list.php"><span class="fa fa-commenting-o">&nbsp;</span> Свободные вопросы</a></li>

		<li><a href="logout.php"><em class="fa fa-power-off">&nbsp;</em> Выйти</a></li>
	</ul>
</div><!--/.sidebar-->