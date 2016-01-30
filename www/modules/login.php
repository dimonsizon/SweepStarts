<?php
defined('ACCESS') or die();
$user			= trim(gs_html($_POST["user"]));
$password		= trim($_POST['pass']);

$get_pass	= mysql_query("SELECT `id`, `login`, `pass`, `status`, `active` FROM `users` WHERE login = '".$user."' LIMIT 1");
$row		= mysql_fetch_array($get_pass);
 $id			= $row['id'];
 $login			= $row['login'];
 $user_password = $row['pass'];
 $status		= $row['status'];
 $active		= $row['active'];

if(!$user || !$password) {
	$er = "";
	include "tpl/login.php";
} elseif(gs_md5($licKEY, $password) != $user_password || !$login) {

	$er		= 1;
	$login	= '';
	include "tpl/login.php";

} elseif($status == 4) {

	print "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\"><script language=\"javascript\">alert('".$lang['loginblock']."'); top.location.href=\"/\";</script></head><body></body></html>";

} elseif($active != 0) {

	$er		= 2;
	$login	= '';
	include "tpl/login.php";

} else {

	$_SESSION['user'] = $login;

	$time	= time();

	mysql_query("UPDATE `users` SET ip = '".$userip."', go_time = ".$time." WHERE login = '".$login."' LIMIT 1");
	mysql_query("INSERT INTO `logip` (`user_id`, `ip`, `date`) VALUES (".$id.", '".$userip."', ".$time.")");

	print "<html><head><script language=\"javascript\">top.location.href=\"/deposits/\";</script></head><body></body></html>";
	
	exit();
}
?>