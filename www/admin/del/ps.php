<?php
include '../../config.php';
include '../../includes/functions.php';

if($status == 1) {
$id = intval($_GET['id']);
	if (mysql_num_rows(mysql_query('SELECT `id` FROM `paysystems` WHERE id = '.$id))) {
		if (mysql_query("DELETE FROM paysystems WHERE id = ".$id." LIMIT 1")) {
			print "<script>alert('��������� ������� �������!'); location.href='../?p=paysystems'</script>";
		} else {
			print "<script>alert('�� ������ ������� ��������� �������!'); location.href='../?p=paysystems'</script>";
		}
	} else {
		print "<script>alert('��� ����� ��������� �������!'); location.href='../?p=paysystems'</script>";
	}
}
?>