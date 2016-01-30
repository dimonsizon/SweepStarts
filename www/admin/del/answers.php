<?php
include "../../config.php";
include "../../includes/functions.php";

if ($status == 1) {
	if ($_GET['id']) {

		$get_inf = mysql_query("SELECT part FROM answers WHERE id = ".intval($_GET['id'])." LIMIT 1");
		$row = mysql_fetch_array($get_inf);
			$part	= $row['part'];

		$sql = 'DELETE FROM answers WHERE `id` = '.intval($_GET['id']).' LIMIT 1';
		if (mysql_query($sql)) {
			print "<html><head><script>alert('Запись удалена!'); top.location.href='/reviews/';</script></head></html>";
		} else {
			print "<html><head><script>alert('Не удаётся удалить запись!'); top.location.href='/reviews/';</script></head></html>";
		}
	} else {
		print "<html><head><script>alert('Не указан комментарий!'); top.location.href='/reviews/';</script></head></html>";
	}
}
?>