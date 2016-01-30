<?php
include "config.php";
include "includes/functions.php";

$m = gs_html($_GET['m']);
$h = gs_html($_GET['h']);

if(!$m || !$h) {
	print '<script language="JavaScript">
	<!--
		alert(\'Error link\');
		top.location.href=\'/\';
	//-->
	</script>';
} else {

	$query	= "SELECT `login`, `mail` FROM `users` WHERE `mail` = '".$m."' LIMIT 1";
	$result	= mysql_query($query);
	$row	= mysql_fetch_array($result);

	if(!$row['mail']) {
		print '<script language="JavaScript">
		<!--
			alert(\'No mail\');
			top.location.href=\'/\';
		//-->
		</script>';
	} elseif($h != gs_md5($licKEY, $row['login'].$row['mail'])) {
		print '<script language="JavaScript">
		<!--
			alert(\'Error activate link\');
			top.location.href=\'/\';
		//-->
		</script>';
	} else {

		mysql_query('UPDATE `users` SET `active` = 0 WHERE `mail` = "'.$row['mail'].'" LIMIT 1');

		print '<html><head><script language="JavaScript">
		<!--
			top.location.href=\'/login/?activate=yes\';
		//-->
		</script></head><body></body>';

	}

}
?>