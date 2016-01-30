<?php
include "../../config.php";
include "../../includes/functions.php";
if($status == 1) {
	$id = intval($_GET['id']);
	mysql_query("DELETE FROM `news` WHERE id = ".$id." LIMIT 1");
	print "<script>alert('Новость удалена!'); location.href='/news/'</script>";
}
?>