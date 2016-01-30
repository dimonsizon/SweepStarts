<?php
include "config.php";
function gs_md5($key, $pass) {
	$pass = md5($key.md5("Z9&".$key."03O".htmlspecialchars($pass, ENT_QUOTES, '')));
return $pass;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="/files/styles.css" rel="stylesheet">
<link href="/files/favicon.ico" type="image/x-icon" rel="shortcut icon">
<title>GoldScript | Инсталляция</title>
</head>
<body>
<table width="100%" height="100%">
	<tr>
		<td align="center">
<?php
if($_GET['action'] == "install") {

$login	= trim(addslashes(htmlspecialchars($_POST["login"], ENT_QUOTES, '')));
$pass	= $_POST["pass"];
$repass	= $_POST["repass"];
$mail	= trim(addslashes(htmlspecialchars($_POST["mail"], ENT_QUOTES, '')));

if(!$login || !$pass || !$repass || !$mail) {
	$error = "<p class=\"er\">Заполните все поля обязательные для заполнения</p>";
} elseif(strlen($login) > 20 || strlen($login) < 3) {
	$error = "<p class=\"er\">Логин должен содержать от 3-х до 20 символов</p>";
} elseif($pass != $repass) {
	$error = "<p class=\"er\">Пароли не совпадают</p>";
} elseif(strlen($mail) > 30) {
	$error = "<p class=\"er\">E-mail должен содержать до 30 символов</p>";
} elseif(!preg_match("/^[a-z0-9_.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is", $mail)) {
	$error = "<p class=\"er\">Введите валидно e-mail</p>";
} else {

print "<p class=\"warn\">Началась установка!</p>";

$sql = mysql_query("CREATE TABLE `blacklist_ip` (
  `id` int(1) NOT NULL auto_increment,
  `ip` varchar(15) NOT NULL default '',
  `comment` varchar(150) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица черного списка IP - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для черного списка IP!</p>";
}

$sql = mysql_query("CREATE TABLE IF NOT EXISTS `msgs` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `fid` int(50) NOT NULL DEFAULT '0',
  `from_id` int(5) NOT NULL DEFAULT '0',
  `from_name` varchar(30) NOT NULL,
  `to_id` int(5) NOT NULL DEFAULT '0',
  `read` smallint(1) NOT NULL DEFAULT '0',
  `date` int(10) NOT NULL DEFAULT '0',
  `subject` varchar(50) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `type` smallint(1) NOT NULL,
  `status` smallint(1) NOT NULL DEFAULT '0',
  `prioritet` smallint(1) NOT NULL DEFAULT '0',
  `comment` varchar(255) NOT NULL,
  `tid` int(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `from_id` (`from_id`),
  KEY `to_id` (`to_id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для сообщений - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для сообщений!</p>";
}

$sql = mysql_query("CREATE TABLE `captcha` (
  `id` int(50) NOT NULL auto_increment,
  `sid` char(32) NOT NULL default '',
  `code` char(32) NOT NULL default '',
  `time` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для капчи - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для капчи!</p>";
}

$sql = mysql_query("CREATE TABLE `promo` (
  `id` smallint(1) NOT NULL auto_increment,
  `type` char(10) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для промо - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для промо!</p>";
}

$sql = mysql_query("CREATE TABLE `deposits` (
  `id` int(1) NOT NULL auto_increment,
  `username` char(30) NOT NULL default '',
  `user_id` int(1) NOT NULL default '0',
  `date` int(1) NOT NULL default '0',
  `plan` smallint(1) NOT NULL default '0',
  `sum` decimal(10,2) NOT NULL default '0.00',
  `paysys` smallint(1) NOT NULL default '0',
  `status` smallint(1) NOT NULL default '0',
  `count` int(1) NOT NULL default '0',
  `lastdate` int(10) NOT NULL default '0',
  `nextdate` int(10) NOT NULL default '0',
  `reinvest` decimal(5,2) NOT NULL default '0.00',
  `bot` smallint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для депозитов - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для депозитов!</p>";
}

$sql = mysql_query("CREATE TABLE `paysystems` (
  `id` smallint(1) NOT NULL auto_increment,
  `name` char(20) NOT NULL default '',
  `purse` char(50) NOT NULL default '',
  `abr` char(10) NOT NULL default '',
  `percent` decimal(10,4) NOT NULL default '0.0000',
  `comment` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для платежных систем - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для платежных систем!</p>";
}

$sql = mysql_query("INSERT INTO `paysystems` VALUES (1, 'PerfectMoney', '', 'PM', 1.00, '')");
$sql = mysql_query("INSERT INTO `paysystems` VALUES (2, 'PAYEER.com', '', 'PE', 1.00, '')");

$sql = mysql_query("CREATE TABLE `enter` (
  `id` int(5) NOT NULL auto_increment,
  `login` char(20) NOT NULL default '',
  `sum` decimal(9,2) NOT NULL default '0.00',
  `date` int(10) NOT NULL default '0',
  `status` smallint(1) NOT NULL default '0',
  `purse` char(50) NOT NULL default '',
  `service` char(50) NOT NULL default '',
  `paysys` char(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для поплнения - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для поплнения!</p>";
}

$sql = mysql_query("CREATE TABLE `transfer` (
  `id` int(5) NOT NULL auto_increment,
  `to` char(30) NOT NULL default '',
  `from` char(30) NOT NULL default '',
  `sum` decimal(9,2) NOT NULL default '0.00',
  `date` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для переводов - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для переводов!</p>";
}

$sql = mysql_query("CREATE TABLE `logip` (
  `id` int(50) NOT NULL auto_increment,
  `user_id` int(5) NOT NULL default '0',
  `date` int(10) NOT NULL default '0',
  `ip` char(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для логов - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для логов!</p>";
}

$sql = mysql_query("CREATE TABLE `news` (
  `id` int(1) NOT NULL auto_increment,
  `subject` varchar(100) NOT NULL default '',
  `msg` text NOT NULL,
  `subject_en` varchar(100) NOT NULL default '',
  `msg_en` text NOT NULL,
  `date` char(10) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для новостей - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для новостей!</p>";
}

$sql = mysql_query("CREATE TABLE `answers` (
  `id` int(1) NOT NULL auto_increment,
  `part` int(1) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `client_id` int(1) NOT NULL default '0',
  `text` text NOT NULL,
  `answer` text NOT NULL,
  `view` smallint(1) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `date` int(10) NOT NULL default '0',
  `yes` smallint(1) NOT NULL default '0',
  `poll` smallint(1) NOT NULL default '0',
  `poll_yes` smallint(1) NOT NULL default '0',
  `poll_no` smallint(1) NOT NULL default '0',
  `poll_count` smallint(1) NOT NULL default '0',
  `comments` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для отзывов - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для отзывов!</p>";
}

$sql = mysql_query("CREATE TABLE `output` (
  `id` int(5) NOT NULL auto_increment,
  `login` char(30) NOT NULL default '',
  `sum` decimal(10,2) NOT NULL default '0.00',
  `purse` char(25) NOT NULL default '',
  `paysys` smallint(1) NOT NULL default '0',
  `date` int(10) NOT NULL default '0',
  `status` smallint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для вывода - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для вывода!</p>";
}

$sql = mysql_query("CREATE TABLE `reflevels` (
  `id` smallint(1) NOT NULL  default '0',
  `sum` decimal(5,2) NOT NULL default '0.00',
  `minsum` decimal(10,2) NOT NULL default '0.00',
  `nominsum` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для реферальной программы - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для реферальной программы!</p>";
}

$sql = mysql_query("INSERT INTO `reflevels` VALUES (1, 10.00)");

$sql = mysql_query("CREATE TABLE `pages` (
  `id` smallint(1) NOT NULL auto_increment,
  `title` varchar(50) NOT NULL default '',
  `keywords` varchar(250) NOT NULL default '',
  `description` varchar(250) NOT NULL default '',
  `body` text NOT NULL,
  `title_en` varchar(50) NOT NULL default '',
  `keywords_en` varchar(250) NOT NULL default '',
  `description_en` varchar(250) NOT NULL default '',
  `body_en` text NOT NULL,
  `path` varchar(20) NOT NULL default '',
  `type` smallint(1) NOT NULL default '0',
  `part` smallint(1) NOT NULL default '0',
  `view` smallint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для страниц - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для страниц!</p>";
}

$sql = mysql_query("INSERT INTO `pages` VALUES (1, 'Главная страница', '', '', '', 'Home page', '', '', '', '', 0, 0, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (2, 'Авторизация', '', '', '', 'Login', '', '', '', 'login', 2, 0, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (3, 'Регистрация', '', '', '', 'Registration', '', '', '', 'registration', 2, 2, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (4, 'Восстановление пароля', '', '', '', 'Forgot your password?', '', '', '', 'reminder', 2, 2, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (5, 'Профиль', '', '', '', 'Profile', '', '', '', 'profile', 2, 0, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (6, 'Пополнение баланса', '', '', '', 'Balance replenishment', '', '', '', 'enter', 0, 0, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (7, 'Открыть депозит', '', '', '', 'Open deposit', '', '', '', 'newdeposit', 0, 0, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (8, 'Ваши вклады', '', '', '', 'Your deposits', '', '', '', 'deposits', 0, 0, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (9, 'Вывод средств', '', '', '', 'Withdrawal', '', '', '', 'withdrawal', 0, 0, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (10, 'Партнерская программа', '', '', '', 'Affiliate', '', '', '', 'affiliate', 0, 0, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (11, 'Статистика', '', '', '', 'Statistics', '', '', '', 'statistics', 0, 0, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (12, 'Перевод средств другому пользователю', '', '', '', 'Transfer of funds to another user', '', '', '', 'transfer', 1, 0, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (13, 'Правила', '', '', '', 'Rules', '', '', '', 'rules', 0, 0, 2)");
$sql = mysql_query("INSERT INTO `pages` VALUES (14, 'Новости', '', '', '', 'News', '', '', '', 'news', 0, 0, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (15, 'Отзывы', '', '', '', 'Reviews', '', '', '', 'reviews', 0, 0, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (16, 'Контакты', '', '', '', 'Contact us', '', '', '', 'support', 0, 0, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (17, 'Ошибка 404', '', '', '<p>Запрашиваемая Вами страница не найдена.<br>Этому могут быть две причины:  ссылка введена неверно, или ранее существовавшая страница была удалена.<br />Перейдите на <a href=\"/\">главную</a>, возможно там найдёте нужную Вам страницу.</p>', 'Error 404', '', '', '<p>The page you requested was not found.<br>This could be two reasons: link is not entered correctly, or pre-existing page has been deleted.<br />Try <a href=\"/\">Home</a>, maybe there find the right page.</p>', '404', 0, 0, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (18, 'Личные сообщения', '', '', '', 'Private messages', '', '', '', 'msg', 0, 0, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (19, 'Сообщение', '', '', '', 'Message', '', '', '', 'readmsg', 0, 18, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (20, 'Новое сообщение', '', '', '', 'New message', '', '', '', 'newmsg', 0, 18, 1)");
$sql = mysql_query("INSERT INTO `pages` VALUES (21, 'Бонус', '', '', '', 'Bonus', '', '', '', 'bonus', 0, 0, 1)");


$sql = mysql_query("CREATE TABLE `settings` (
  `id` smallint(1) NOT NULL auto_increment,
  `cfgname` varchar(50) NOT NULL default '',
  `data` varchar(255) NOT NULL default '',
  `description` char(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для настроек - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для настроек!</p>";
}

$sql = mysql_query("INSERT INTO `settings` VALUES (1, 'adminmail', '".$mail."', 'E-mail администратора')");
$sql = mysql_query("INSERT INTO `settings` VALUES (2, 'projectname', 'GoldScript', 'Название проекта')");
$sql = mysql_query("INSERT INTO `settings` VALUES (3, 'cfgPerfect', '', 'PerfectMoney кошелек')");
$sql = mysql_query("INSERT INTO `settings` VALUES (4, 'cfgPAYEE_NAME', '', 'PAYEE_NAME в PM для приема средств')");
$sql = mysql_query("INSERT INTO `settings` VALUES (5, 'regcaptcha', 'on', 'Включение каптчи при регистрации')");
$sql = mysql_query("INSERT INTO `settings` VALUES (6, 'ALTERNATE_PHRASE_HASH', '', 'MD5 ALTERNATE_PHRASE_HASH для PM')");
$sql = mysql_query("INSERT INTO `settings` VALUES (7, 'cfgAutoPay', 'off', 'Автовыплаты (on - включены; off - вЫключены)')");
$sql = mysql_query("INSERT INTO `settings` VALUES (8, 'cfgPMID', '', 'ID PerfectMoney')");
$sql = mysql_query("INSERT INTO `settings` VALUES (9, 'cfgPMpass', '', 'Пароль от PerfectMoney')");
$sql = mysql_query("INSERT INTO `settings` VALUES (10, 'cfgRefPerc', '1', 'Партнерские отчисления')");
$sql = mysql_query("INSERT INTO `settings` VALUES (11, 'cfgMinOut', '1.00', 'Минимальная сумма на вывод')");
$sql = mysql_query("INSERT INTO `settings` VALUES (12, 'cfgPercentOut', '0', 'Процент, который высчитывает система при выводе')");
$sql = mysql_query("INSERT INTO `settings` VALUES (13, 'cfgLang', 'ru', 'Язык по умолчанию')");
$sql = mysql_query("INSERT INTO `settings` VALUES (14, 'datestart', '".time()."', 'Дата старта проекта')");
$sql = mysql_query("INSERT INTO `settings` VALUES (15, 'fakeusers', '0', 'Накрутка фэйковых пользователей')");
$sql = mysql_query("INSERT INTO `settings` VALUES (16, 'fakeactiveusers', '0', 'Накрутка фэйковых активных пользователей')");
$sql = mysql_query("INSERT INTO `settings` VALUES (17, 'fakeonline', '0', 'Накрутка пользователей онлайн')");
$sql = mysql_query("INSERT INTO `settings` VALUES (18, 'fakedeposits', '0', 'Накрутка суммы депозитов')");
$sql = mysql_query("INSERT INTO `settings` VALUES (19, 'fakewithdraws', '0', 'Накрутка суммы вывода')");
$sql = mysql_query("INSERT INTO `settings` VALUES (20, 'autopercent', 'on', 'Вкл/Выкл начисление процентов')");
$sql = mysql_query("INSERT INTO `settings` VALUES (21, 'cfgOutAdminPercent', '0.00', 'Процент перевода на админский кошелек')");
$sql = mysql_query("INSERT INTO `settings` VALUES (22, 'AdminPMpurse', '', 'PerfectMoney админский счет')");
$sql = mysql_query("INSERT INTO `settings` VALUES (23, 'refname', 'partner', 'Реферальная ссылка')");
$sql = mysql_query("INSERT INTO `settings` VALUES (24, 'cfgReInv', 'off', 'Реинвестиции (on - включены; off - вЫключены)')");
$sql = mysql_query("INSERT INTO `settings` VALUES (25, 'cfgSSL', 'off', 'Работа по https:// (on - включено; off - вЫключено)')");
$sql = mysql_query("INSERT INTO `settings` VALUES (26, 'cfgMailConf', 'off', 'Подтверждение регистрации по e-mail (on - включено; off - вЫключено)')");
$sql = mysql_query("INSERT INTO `settings` VALUES (27, 'cfgTrans', 'off', 'Включение перевода денег между пользователями (on - включено; off - вЫключено)')");
$sql = mysql_query("INSERT INTO `settings` VALUES (28, 'cfgTransPercent', '0', 'Процент от суммы перевода системе')");
$sql = mysql_query("INSERT INTO `settings` VALUES (29, 'cfgInstant', 'off', 'Включение выплат инстантом (on - включено; off - вЫключено)')");
$sql = mysql_query("INSERT INTO `settings` VALUES (30, 'cfgOnOff', 'on', 'Включение/выключение сайта (on - включено; off - вЫключено)')");
$sql = mysql_query("INSERT INTO `settings` VALUES (31, 'cfgMaxOut', '10000', 'Максимум на вывод средств')");
$sql = mysql_query("INSERT INTO `settings` VALUES (32, 'cfgCountOut', '0', 'Кол-во раз вывода средств в сутки одним пользователем')");
$sql = mysql_query("INSERT INTO `settings` VALUES (33, 'cfgPEsid', '', 'ID магазина в системе PAYEER')");
$sql = mysql_query("INSERT INTO `settings` VALUES (34, 'cfgPEkey', '', 'Секретный ключ в системе PAYEER')");
$sql = mysql_query("INSERT INTO `settings` VALUES (35, 'cfgAutoPayPE', 'off', 'Автовыплаты PAYEER (on - включены; off - вЫключены)')");
$sql = mysql_query("INSERT INTO `settings` VALUES (36, 'cfgPEAcc', '', 'Номер счета PAYEER')");
$sql = mysql_query("INSERT INTO `settings` VALUES (37, 'cfgPEidAPI', '', 'Номер магазина в API в системе Payeer')");
$sql = mysql_query("INSERT INTO `settings` VALUES (38, 'cfgPEapiKey', '', 'Секретный ключ в API в системе Payeer')");

$sql = mysql_query("INSERT INTO `settings` VALUES (39, 'cfgMonCur', 'USD', 'Валюта проекта')");

$sql = mysql_query("INSERT INTO `settings` VALUES (40, 'cfgBonusBal', 'off', 'Включение бонусного баланса')");
$sql = mysql_query("INSERT INTO `settings` VALUES (41, 'cfgBonusReg', '0.00', 'Бонус при регистрации')");

$sql = mysql_query("INSERT INTO `settings` VALUES (42, 'cfgBonusOnOff', 'off', 'Переодичный бонус')");
$sql = mysql_query("INSERT INTO `settings` VALUES (43, 'cfgBonusMin', '1', 'Минимальная сумма бонуса')");
$sql = mysql_query("INSERT INTO `settings` VALUES (44, 'cfgBonusMax', '100', 'Максимальная сумма бонуса')");
$sql = mysql_query("INSERT INTO `settings` VALUES (45, 'cfgBonusPeriod', '1440', 'Переодичность выдачи бонуса в минутах')");

$sql = mysql_query("CREATE TABLE `plans` (
  `id` smallint(1) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `minsum` decimal(10,2) NOT NULL default '0.00',
  `maxsum` decimal(10,2) NOT NULL default '0.00',
  `percent` decimal(8,4) NOT NULL default '0.0000',
  `bonusdeposit` decimal(5,2) NOT NULL default '0.00',
  `bonusbalance` decimal(5,2) NOT NULL default '0.00',
  `period` smallint(1) NOT NULL default '0',
  `days` smallint(1) NOT NULL default '0',
  `back` smallint(1) NOT NULL default '1',
  `reinvest` smallint(1) NOT NULL default '0',
  `weekend` smallint(1) NOT NULL default '0',
  `status` smallint(1) NOT NULL default '0',
  `close` smallint(1) NOT NULL default '0',
  `close_percent` decimal(5,2) NOT NULL default '0.00',
  `img` smallint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для планов - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для планов!</p>";
}

$sql = mysql_query("CREATE TABLE IF NOT EXISTS `bonus` (
  `id` smallint(1) NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL,
  `refsum` decimal(9,2) NOT NULL DEFAULT '0.00',
  `sum` decimal(9,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для бонусов - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для бонусов!</p>";
}

$sql = mysql_query("CREATE TABLE IF NOT EXISTS `bonuses` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(1) NOT NULL default '0',
  `date` int(10) NOT NULL default '0',
  `login` char(30) NOT NULL,
  `sum` decimal(9,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для начисления бонусов - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для начисления бонусов!</p>";
}

$sql = mysql_query("CREATE TABLE `stat` (
  `id` int(10) NOT NULL auto_increment,
  `user_id` int(1) NOT NULL default '0',
  `date` int(1) NOT NULL default '0',
  `plan` smallint(1) NOT NULL default '0',
  `sum` decimal(10,2) NOT NULL default '0.00',
  `paysys` smallint(1) NOT NULL default '0',
  `type` smallint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для статистики - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для статистики!</p>";
}

$sql = mysql_query("CREATE TABLE `geoip_db` (
  `start` bigint(10) unsigned NOT NULL,
  `end` bigint(10) unsigned NOT NULL,
  `cc` char(2) NOT NULL,
  KEY `start` (`start`),
  KEY `end` (`end`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для GeoIP - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для GeoIP!</p>";
}

$sql = mysql_query("CREATE TABLE `loghistory` (
	`id` int(10) NOT NULL auto_increment,
	`date` int(1) NOT NULL default '0',
	`user_id` int(1) NOT NULL default '0',
	`login` char(20) NOT NULL default '',
	`ip` char(15) NOT NULL default '',
	`action` char(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для логов просмотров страниц - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для логов просмотра страниц!</p>";
}

$sql = mysql_query("CREATE TABLE `accounting` (
  `id` int(10) NOT NULL auto_increment,
  `date` int(1) NOT NULL default '0',
  `ccat` smallint(1) NOT NULL default '0',
  `description` varchar(100) NOT NULL default '',
  `sum` decimal(10,2) NOT NULL default '0.00',
  `dc` smallint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица для бухгалтерии - создана!</p>";
} else {
	print "<p class=\"er\">Не удалось создать таблицу для бухгалтерии!</p>";
}

$sql = mysql_query("CREATE TABLE `users` (
  `id` int(1) NOT NULL auto_increment,
  `login` char(20) NOT NULL default '',
  `pass` char(32) NOT NULL default '',
  `mail` char(30) NOT NULL default '',
  `reg_time` int(10) NOT NULL default '0',
  `go_time` int(10) NOT NULL default '0',
  `ip` char(15) NOT NULL default '',
  `status` smallint(1) NOT NULL default '0',
  `comment` char(150) NOT NULL default '',
  `balance` decimal(10,2) NOT NULL default '0.00',
  `bonus` decimal(10,2) NOT NULL default '0.00',
  `bonuslevel` smallint(1) NOT NULL default '0',
  `clx` int(1) NOT NULL default '0',
  `ref` int(1) NOT NULL default '0',
  `ref_money` decimal(10,2) NOT NULL default '0.00',
  `reftop` decimal(10,2) NOT NULL default '0.00',
  `ref_percent` decimal(10,2) NOT NULL default '0.00',
  `pm` char(10) NOT NULL default '',
  `pe` char(50) NOT NULL default '',
  `icq` char(20) default NULL,
  `skype` char(50) default NULL,
  `active` smallint(1) NOT NULL default '0',
  `bot` smallint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `login` (`login`)
) ENGINE=MyISAM");

if($sql) {
	print "<p class=\"erok\">Таблица пользователей - создана!</p>";

// Создаём администраторов
$sql = mysql_query("INSERT INTO `users` (`login`, `pass`, `mail`, `reg_time`, `status`) VALUES('".$login."', '".gs_md5($licKEY, $pass)."', '".$mail."', ".time().", 1)");

// Закончили создание админов

if($sql) {
	print "<p class=\"erok\">Администратор - создан!</p>";
} else {
	print "<p class=\"er\">Не удалось создать администратора!</p>";
}

} else {
	print "<p class=\"er\">Не удалось создать таблицу пользователей!</p>";
}

print "<p class=\"er\">Удалите файл install.php!</p>";
}
}

if(!$_GET['action'] || ($_GET['action'] == "install" && $error)) {
	print $error;
?>


			<table width="300" height="60" cellpadding="0" cellspacing="0" border="0" bgcolor="#eeeeee" style="border-radius: 6px; border: 1px solid #666666;">
				<tr>
					<td align="center" style="padding: 10 10 10 10px;">
						<form action="?action=install" method="post">
						<table cellspacing="1" cellpadding="0" border="0" width="100%">
							<tr>
								<td>Логин администратора:<td>
							</tr>
							<tr>
								<td><input class="input" style="width: 100%;" type="text" name="login" size="20" maxlength="20" /><td>
							</tr>
							<tr>
								<td>Пароль:<td>
							</tr>
							<tr>
								<td><input class="input" style="width: 100%;" type="password" name="pass" size="20" maxlength="20" /><td>
							</tr>
							<tr>
								<td>Пароль [повторно]:<td>
							</tr>
							<tr>
								<td><input class="input" style="width: 100%;" type="password" name="repass" size="20" maxlength="20" /><td>
							</tr>
							<tr>
								<td>E-mail:<td>
							</tr>
							<tr>
								<td><input class="input" style="width: 100%;" type="text" name="mail" size="20" maxlength="30" /><td>
							</tr>
							<tr height="5">
								<td><td>
							</tr>
							<tr>
								<td><input class="input" style="width: 100%;" type="submit" value=" Инсталлировать " /><td>
							</tr>
						</table>
						</form>
					</td>
				</tr>
			</table>


<?php
}
?>
		</td>
	</tr>
	<tr height="25">
		<td align="center">
			<font color="#999999">&copy; <?php print date(Y); ?> <a href="http://goldscript.ru/" target="_blank">GoldScript</a></font>
		</td>
	</tr>
</table>
</body>
</html>