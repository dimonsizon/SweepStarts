<?php
/*
���������������� ���� ������� GoldScript.
��� ������ ���������� ������� �� ����������� �������.
www.goldscript.ru
*/

Error_Reporting(0);

// ������ ��� ����������� � �� MySql

	$mysqlhostname	= "localhost";		// ���� � �� MySql
	$mysqllogin		= "root";			// ����� � �� MySql
	$mysqlpassword	= "";				// ������ � �� MySql
	$mysqldatabase	= "sweepstarts";		// ���� ������ MySql

// ������ ��������.

  $licID			= 1;									// ID ��������
  $licKEY			= "0W40�-0N6E�-W0HT�-786�3-����7";		// ������������ ����
  $licHASH			= "434dc8a33d7f9c85eb44fdf778619802";	// MD5 hash ��������

// ���������� � �� MySql
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