<?php
include "../../config.php";
include "../../includes/functions.php";
if($status == 1) {
	$id = intval($_GET['id']);
	mysql_query("DELETE FROM `users` WHERE id = ".$id." LIMIT 1");

	print "<html><head><script language=\"javascript\">alert('������������ �����!'); location.href='../?p=users&page=".$_GET['page']."'</script></head></html>";
} else {
	print "<html><head><script language=\"javascript\">alert('� ��� ��� ���� �� ���������� ������ ��������!'); location.href='../?p=users&page=".$_GET['page']."'</script></head></html>";
}
?>