<?php
include "../../config.php";
include "../../includes/functions.php";
if($status == 1) {
	$f = gs_html($_GET['f']);
	if(!$f) {
		print "<html><head><script language=\"javascript\">alert('�� �� ������� ����� �������� ���������� �������'); top.location.href='../?p=pages';</script></head></html>";
	} else {
		mysql_query("DELETE FROM pages WHERE path = '".$f."' LIMIT 1");
		print "<html><head><script language=\"javascript\">alert('�������� �������!'); top.location.href='../?p=pages';</script></head></html>";
	}
} else {
	include "../../includes/errors/404.php";
}
?>