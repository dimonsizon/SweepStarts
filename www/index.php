<?php
	Error_Reporting(0);
	$page	= substr(htmlspecialchars($_GET['page'], ENT_QUOTES, ''),0,15);
	include "config.php";
	include "includes/functions.php";
	include "tpl/template.php";
?>