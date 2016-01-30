<?php
/*
Конфигурационный файл скрипта GoldScript.
Все данные необходимо указать ДО инсталляции скрипта.
www.goldscript.ru
*/

Error_Reporting(0);

// Данные для подключения к БД MySql

	$mysqlhostname	= "localhost";		// Хост к БД MySql
	$mysqllogin		= "root";			// Логин к БД MySql
	$mysqlpassword	= "";				// Пароль к БД MySql
	$mysqldatabase	= "sweepstarts";		// База данных MySql

// Данные лицензии.

  $licID			= 1;									// ID лицензии
  $licKEY			= "0W40А-0N6EК-W0HTА-786В3-РВЕМ7";		// Лицензионный ключ
  $licHASH			= "434dc8a33d7f9c85eb44fdf778619802";	// MD5 hash лицензии

// Соединение с БД MySql
if (!($conn = mysql_connect($mysqlhostname, $mysqllogin , $mysqlpassword))) {
	include "includes/errors/db.php";
	exit();
} else {
	if (!(mysql_select_db($mysqldatabase, $conn))) {
		include "includes/errors/db.php";
		exit();
	}
}

mysql_query("SET NAMES 'cp1251'");
define('ACCESS', true);
?>