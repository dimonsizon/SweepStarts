<?php
session_start();
include "../config.php";

$n1		= rand(1,9);
$n2		= rand(0,9);
$n3		= rand(0,9);

$sid	= htmlspecialchars(substr(session_id(),0,32), ENT_QUOTES, '');
$code	= md5($n1.$n2.$n3);

mysql_query("DELETE FROM `captcha` WHERE `sid` = '".$sid."'");
mysql_query("INSERT INTO `captcha` (`sid`, `code`, `time`) VALUES ('".$sid."', '".$code."', ".time().")");

// Генерируем картинку

header("Content-type: image/jpeg");

$img_path			= "captcha.jpg";
$img				= ImageCreateFromJpeg($img_path);

$c = rand(1,7);

if($c == 1) {
	$color	= imagecolorallocate($img, 0, 0, 0);
} elseif($c == 2) {
	$color	= imagecolorallocate($img, 102, 0, 0);
} elseif($c == 3) {
	$color	= imagecolorallocate($img, 0, 102, 56);
} elseif($c == 4) {
	$color	= imagecolorallocate($img, 0, 51, 204);
} elseif($c == 5) {
	$color	= imagecolorallocate($img, 255, 102, 0);
} elseif($c == 6) {
	$color	= imagecolorallocate($img, 102, 51, 51);
} elseif($c == 7) {
	$color	= imagecolorallocate($img, 0, 153, 153);
}

// Выводим название проекта
imagettftext($img, rand(14, 16), 0, 10, rand(20, 38), $color, 'fonts/'.rand(1,22).'.ttf', $n1);
imagettftext($img, rand(14, 16), 0, 30, rand(20, 38), $color, 'fonts/'.rand(1,22).'.ttf', $n2);
imagettftext($img, rand(14, 16), 0, 50, rand(20, 23), $color, 'fonts/'.rand(1,22).'.ttf', $n3);

imagejpeg($img);
imagedestroy($img);
?>