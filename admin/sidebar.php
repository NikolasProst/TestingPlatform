<?php
    $url = $_SERVER['REQUEST_URI'];
    $path = parse_url($url, PHP_URL_PATH);
    $filename = basename($path);
    $test_menu = array('tests.php', 'addTest.php', 'viewTest.php', 'addQuestion.php');
    $user_menu = array('testResult.php', 'users.php');
?>

<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
	<ul class="nav menu">
		<li <?php if($filename == 'index.php'): ?>class="current"<?php endif; ?>><a href="index.php"><em class="fa fa-dashboard">&nbsp;</em> Главная</a></li>
		<li class="parent <?php if(in_array($filename,$test_menu)): ?>current<?php endif; ?>">
			<a data-toggle="collapse" href="#sub-item-1">
				<em class="fa fa-navicon">&nbsp;</em> Тесты<span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="fa fa-plus"></em></span>
			</a>
			<ul class="children collapse" id="sub-item-1">
				<li <?php if($filename == 'tests.php'): ?>class="current"<?php endif; ?> >
					<a href="tests.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Просмотр тестов
					</a>
				</li>
				<li <?php if($filename == 'addTest.php'): ?>class="current"<?php endif; ?> >
					<a href="addTest.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Добавить тест
					</a>
				</li>
				<li <?php if($filename == 'addCompetence.php'): ?>class="current"<?php endif; ?> >
					<a href="addCompetence.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Добавить компетенцию
					</a>
				</li>
				<li <?php if($filename == 'addSubject.php'): ?>class="current"<?php endif; ?> >
					<a href="addSubject.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Добавить предмет
					</a>
				</li>
				<li <?php if($filename == 'addSpecialization.php'): ?>class="current"<?php endif; ?> >
					<a href="addSpecialization.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Добавить направление
					</a>
				</li>
				<li <?php if($filename == 'addConnectSpecToSubj.php'): ?>class="current"<?php endif; ?> >
					<a href="addConnectSpecToSubj.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Привязка направления
					</a>
				</li>
				<li <?php if($filename == 'addConnectSubjToComp.php'): ?>class="current"<?php endif; ?> >
					<a href="addConnectSubjToComp.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Привязка предмета
					</a>
				</li>
			</ul>
		<li class="parent <?php if(in_array($filename,$user_menu)): ?>current<?php endif; ?>">
			<a data-toggle="collapse" href="#sub-item-2">
				<em class="fa fa-users">&nbsp;</em> Статистика<span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="fa fa-plus"></em></span>
			</a>
			<ul class="children collapse" id="sub-item-2">
				<li <?php if($filename == 'users.php'): ?>class="current"<?php endif; ?> >
					<a href="users.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Статистика по тестам
					</a>
				</li>
				<li <?php if($filename == 'users.php'): ?>class="current"<?php endif; ?> >
					<a href="users.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Show Users
					</a>
				</li>
			</ul>
		</li>		</li>

		<li><a href="logout.php"><em class="fa fa-power-off">&nbsp;</em> Выйти</a></li>
	</ul>
</div><!--/.sidebar-->