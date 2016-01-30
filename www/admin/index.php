<?php
include "../config.php";
include "../includes/functions.php";
if(($status == 1 || $status == 2) && $login) {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title>GoldScript || Лицензия «<?php print $cfgURL; ?>»</title>
<link href="/files/favicon.ico" type="image/x-icon" rel="shortcut icon" />
<link href="files/css/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<!--
	var h=<?php print intval(date('G')); ?>;
	var m=<?php print intval(date('i')); ?>;
	var s=<?php print intval(date('s')); ?>;
	setInterval("showtime()",1000);

	function showtime() {
		s++;
		if (s>=60) {
			s=0;
			m++;
			if (m>=60) {
				m=0;
				h++;
				if (h>=24) h=0;
			}
		}
		s = s+"";
		m = m+"";
		h = h+"";
		if (s.length<2) s = "0"+s;
		if (m.length<2) m = "0"+m;
		if (h.length<2) h = "0"+h;
		document.getElementById("time").innerHTML = h+":"+m+":"+s;
	}
-->
</script>
</head>
<body>
<div id="wrap">
	<div id="header">
		<div class="left"><a href="/admin"><img src="images/logo.png" width="256" height="50" border="0" alt="GoldScript"></a></div>
		<div class="right">
			<div class="block">
		Здравствуй, <?php print $login; ?> [#<?php print $user_id; ?>]! IP: <?php print $userip; ?> <img src="/img/flags/<?php print getCOUNTRY($userip); ?>.gif" width="18" height="12" border="0" alt="" /><br />Серверное время: <b><?php print date("d.m.Y"); ?></b> <span id="time"></span><br /><a href="/">Перейти на сайт</a> | <a href="javascript: if(confirm('Вы действительно хотите выйти?')) top.location.href='/logout.php';">Выход</a>
			</div>
		</div>
	</div>
	<div class="clear"></div>
	<ul class="menu">
		<li><a href="?p=settings"><img align="texttop" src="images/set_ico.png" alt=""> Настройки проекта</a></li>
		<li><a href="?p=plans"><img align="texttop" src="images/tarif_ico.png" alt=""> Тарифные планы</a></li>
		<li><a href="?p=deposits"><img align="texttop" src="images/vklad_ico.png" alt=""> Вклады</a></li>
		<li><a href="?p=info"><img align="texttop" src="images/instrument_ico.png" alt=""> Инструменты</a></li>
	</ul>
	<ul class="menu">
		<li><a href="?p=pages"><img align="texttop" src="images/content_ico.png" alt=""> Управление контентом</a></li>
		<li><a href="?p=news"><img align="texttop" src="images/news_ico.png" alt=""> Добавить новость</a></li>
		<li><a href="?p=mail"><img align="texttop" src="images/mail_ico.png" alt=""> Рассылка сообщений</a></li>
		<li><a href="?p=add_page"><img align="texttop" src="images/addpage_ico.png" alt=""> Создать страницу</a></li>
	</ul>
	<ul class="menu">
		<li><a href="?p=users"><img align="texttop" src="images/users_ico.png" alt=""> Пользователи</a></li>
		<li><a href="?p=ref"><img align="texttop" src="images/ref_ico.png" alt=""> Реферальная система</a></li>
		<li><a href="?p=topref"><img align="texttop" src="images/reftop_ico.png" alt=""> Рейтинг рефоводов</a></li>
		<li><a href="?p=stat"><img align="texttop" src="images/stat_ico.png" alt=""> Управление статистикой</a></li>
	</ul>
	<ul class="menu">
		<li><a href="?p=merchant"><img align="texttop" src="images/setpay_ico.png" alt=""> Настройки мерчанта</a></li>
		<li><a href="?p=paysystems"><img align="texttop" src="images/paysystem_ico.png" alt=""> Платежные системы</a></li>
		<li><a href="?p=enter"><img align="texttop" src="images/enter_ico.png" alt=""> Пополнение счета [ <?php print mysql_num_rows(mysql_query("SELECT `id` FROM `enter` WHERE `status` = 1")); ?> ]</a></li>
		<li><a href="?p=withdrawal"><img align="texttop" src="images/out_ico.png" alt=""> Вывод средств [ <?php print mysql_num_rows(mysql_query("SELECT `id` FROM `output` WHERE `status` = 0")); ?> ]</a></li>
	</ul>
	<ul class="menu">
		<li><a href="?p=blacklist"><img align="texttop" src="images/blacklist_ico.png" alt=""> Черный список IP</a></li>
		<li><a href="?p=logip"><img align="texttop" src="images/monip_ico.png" alt=""> Мониторинг IP</a></li>
		<li><a href="?p=logauth"><img align="texttop" src="images/logip_ico.png" alt=""> Лог авторизаций</a></li>
		<li><a href="?p=logpages"><img align="texttop" src="images/logpage_ico.png" alt=""> Лог посещений</a></li>
	</ul>
	<div class="clear"></div>
	<div id="content">

<?php

	$p	= gs_html($_GET['p']);

	if(!$p) {
		include "modules/index.php";
	} elseif(file_exists("modules/".$p.".php")) {
		include "modules/".$p.".php";
	} else {
		include "modules/error.php";
	}

?>
	</div>
</div>
<div id="footer">&copy; 2014 - <?php print date(Y); ?> <a href="http://goldscript.ru" target="_blank">GoldScript</a><br />Все права защищены!</div>
</body>
</html>
<?php
} else {
	include "../includes/errors/404.php";
}
?>