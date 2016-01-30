<?php
include '../../config.php';
include '../../includes/functions.php';

if($status == 1) {
$id = intval($_GET['id']);
	if (mysql_num_rows(mysql_query('SELECT `id` FROM plans WHERE id = '.$id.' LIMIT 1'))) {
		if (mysql_query("DELETE FROM `plans` WHERE `id` = ".$id." LIMIT 1")) {
			print "<script>alert('Тарифный план удалён!'); location.href='../?p=plans'</script>";
		} else {
			print "<script>alert('Не удаётся удалить тарифный план!'); location.href='../?p=plans'</script>";
		}
	} else {
		print "<script>alert('Нет такого тарифного плана!'); location.href='../?p=plans'</script>";
	}
}
?>