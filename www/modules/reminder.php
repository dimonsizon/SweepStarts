<?php
defined('ACCESS') or die();
print $body;
function generator($case1, $case2, $case3, $case4, $num1) {
	$password = "";

	$small="abcdefghijklmnopqrstuvwxyz";
	$large="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$numbers="1234567890";
	$symbols="~!#$%^&*()_+-=,./<>?|:;@";
	mt_srand((double)microtime()*1000000);

	for ($i=0; $i<$num1; $i++) {

		$type = mt_rand(1,4);
		switch ($type) {
		case 1:
			if ($case1 == "on") { $password .= $large[mt_rand(0,25)]; } else { $i--; }
			break;
		case 2:
			if ($case2 == "on") { $password .= $small[mt_rand(0,25)]; } else { $i--; }
			break;
		case 3:
			if ($case3 == "on") { $password .= $numbers[mt_rand(0,9)]; } else { $i--; }
			break;
		case 4:
			if ($case4 == "on") { $password .= $symbols[mt_rand(0,24)]; } else { $i--; }
			break;
		}
	}
	return $password;
}

if($_GET['action'] == "send" AND isset($_POST['email']) AND isset($_POST['ulogin'])) {
	$email	= gs_html($_POST['email']);
	$ulogin = gs_html($_POST['ulogin']);
	$code	= md5(gs_html($_POST["code"]));
	if(!mysql_num_rows(mysql_query("SELECT * FROM `captcha` WHERE sid = '".$sid."' AND code = '".$code."'"))) {
			print "<p class=\"er\">".$lang['er_05']."</p>";
	}  elseif(preg_match("/^[a-z0-9_.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is", $email)) {
		$sql	= 'SELECT `login`, `pass`, `status` FROM `users` WHERE `mail` = "'.$email.'" AND `login` = "'.$ulogin.'" LIMIT 1';
		$rs		= mysql_query($sql);
		$a		= mysql_fetch_array($rs);
		$s		= $a['status'];

		if (!$a) {
			print '<p class="er">'.$lang['er_06'].'</p>';
		} else {

			$case1	= on;
			$case2	= on;
			$case3	= on;
			$case4	= off;
			$num1	= 8;
			$num2	= 1;

			$newpass = generator($case1, $case2, $case3, $case4, $num1);

			$text = "<p>Здравствуйте <b>".$a['login']."</b>!</p><p>По Вашей просьбе высылаем новый пароль к аккаунту ".$a['login']."<br /><p>Новый пароль: <b>".$newpass."</b></p>С Уважением, администрация проекта ".$cfgURL."<hr /><p>Hello <b>".$a['login']."</b>!</p><p>At your request, send new password to your account ".$a['login']."<br /><p>New password: <b>".$newpass."</b></p>Sincerely, Administration project <a href=\"http://".$cfgURL."\">".$cfgURL."</a>";

			$subject = "New password / Новый пароль";
			$headers = "From: ".$adminmail."\n";
			$headers .= "Reply-to: ".$adminmail."\n";
			$headers .= "X-Sender: < http://".$cfgURL." >\n";
			$headers .= "Content-Type: text/html; charset=windows-1251\n";

			mysql_query("UPDATE `users` SET pass = '".gs_md5($licKEY, $newpass)."' WHERE login = '".$a['login']."' LIMIT 1");
			if (mail($email,$subject,$text,$headers)) {
				print '<p class="erok">'.$lang['er_07'].'</p>';
			} else {
				print '<p class="er">'.$lang['er_08'].'</p>';
			}
		}
	} else {
		print '<p class="er">'.$lang['er_09'].'</p>';
	}
}
include "tpl/reminder.php";
?>