<?php
include "../../config.php";
include "../../includes/functions.php";

if($status == 1) {

	$file = fopen("http://goldscript.ru/ip.php", "r");

	if($file) {

		$file = fread($file, 50);
		print "<p class=\"erok\">".$file."</p>";

	} else {
		print "<p class=\"er\">Не удалось определить. Попробуйте позже.</p>";
	}

}
?>