<?php
include "../../config.php";
include "../../includes/functions.php";

if ($status == 1) {
	if ($_GET['id']) {
		$sql = "UPDATE output SET status = 3 WHERE id = ".intval($_GET['id'])." LIMIT 1";
		if (mysql_query($sql)) {
			echo "<html><head><script>alert('Отказ выполнен!'); top.location.href='../?p=withdrawal';</script></head></html>";
		} else {
			echo "<html><head><script>alert('Не удаётся удалить запись!'); top.location.href='../?p=withdrawal';</script></head></html>";
		}
	} else {
		echo "<html><head><script>alert('Не указана запись!'); top.location.href='../?p=withdrawal';</script></head></html>";
	}
}
?>